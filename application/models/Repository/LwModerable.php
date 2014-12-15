<?php
namespace Repository;
use Application\App;
/**
 * @see https://crs-api.liveworld.com/
 * @see https://crs-api.liveworld.com/LiveWorld_Content_Review_System-API_Reference.pdf
 */
abstract class LwModerable extends BaseModerable
{
    /**
     * @return \Application\models\LwAdapter
     */
    public function getLwAdapter()
    {
        App::$inst->container->singleton('LwModerable_lwAdapter', function () {
            return new \Application\models\LwAdapter();
        });
        return App::$inst->LwModerable_lwAdapter;
    }
    
    /**
     * @return array [\Entity\LwModerable,\Entity\LwModerable, ...]
     */
    public function getToApproveLW()
    {
        $moderableData = $this->_em->createQueryBuilder()
        ->select($this->_entityName)
        ->from($this->getClassName(), $this->_entityName)
        ->andWhere($this->_entityName.'.status = '.\Entity\LwModerable::STATUS_WAITING_TO_SEND)
        ->setMaxResults(100)
        ->getQuery()
        ->getResult();
        
        return $moderableData;
    }
    
    /**
     * 
     */
    public function sendToLw()
    {
        App::$inst->log->info(__METHOD__ . ' started');
        $appConfig = App::$inst->config;
    
        $posts = $this->getToApproveLW();
        $adapter = $this->getLwAdapter();
    
        foreach ($posts as $post)
        {
            try {
                method_exists($post, 'init') && $post->init();
                $post->getLwTrackingId(true);
                $post['lw_mod_id'] = $adapter->sendModerationContent($post);
                App::$inst->log->info('handled feed with id ' . $post['id'] . ' with result ' . $post['lw_mod_id']);
                if($post['lw_mod_id']) {
                    $post['status'] = \Entity\LwModerable::STATUS_PENDING;
                    $post['updated_by'] = \Entity\LwModerable::UPDATED_BY_LW;
                    $this->update($post);
                }
            } catch (\Exception $e) {
                 App::$inst->log->error($e);
            }
        }
        App::$inst->log->info(__METHOD__ . ' finished');
    }
    
    public function receiveFromLw($xmlFile = null)
    {
        $appConfig = App::$inst->config;
        $adapter = $this->getLwAdapter();
        $response = array();
        try {
            $res = $adapter->getModerationContent($xmlFile);
            if ($res === null) {
                throw new Exception($adapter->getError());
            }
            $response['input'] = $adapter->getError();
    
            if (is_array(@$res['com.liveworld.moderation.web.struts.rest.ModerationContent'])) {
                $list = array_shift($res);
            } else {
                $list = $res;
            }
    
            if (! is_array($list)) {
                $response['notarray'] = $res;
                throw new \Exception('Moderation is not array');
            }
            $toConfirm = array();
            foreach ($list as $item) {
                $response['all'][] = $item->id . ':' . $item->moderation__status;
                switch ((int) $item->moderation__status & $adapter::MASK) {
                    case $adapter::LW_MANUAL_STATUS_APPROVED:
                    case $adapter::STATUS_APPROVED:
                        if ($this->approveEntry((string) $item->id)) {
                            $response['updated'][(string) $item->id] = true;
                            $toConfirm[] = $item->tracking__id;
                        } else {
                            $response['updated'][(string) $item->id] = false;
                        }
                        break;
                    case $adapter::LW_MANUAL_STATUS_REJECTED:
                    case $adapter::STATUS_REJECTED:
                        if ($this->rejectEntry((string) $item->id)) {
                            $response['rejected'][(string) $item->id] = true;
                            $toConfirm[] = $item->tracking__id;
                        } else {
                            $response['rejected'][(string) $item->id] = false;
                        }
                        break;
                    default:
                        ;
                }
            }
            $response['confirmation'] = $adapter->sendConfirmations($toConfirm);
            $response['found'] = count($list);
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }
        return $response;
    }
    
    /**
     * @param mixed<int|string> $id
     * @param boolean $manual
     * @throws \Exception
     * @return boolean
     */
    public function approveEntry($id, $manual = false)
    {
        try {
            $key = is_int($mod_id) ? 'id' : 'lw_mod_id';
            $post = $this->findOneBy(array($key => $mod_id));
            if (!$post) {
                throw new \Exception(__METHOD__ . ': Cannot find entry with lw_mod_id' . $mod_id);
            }
            $post['status'] = \Entity\BaseModerable::STATUS_APPROVED;
            if (!$manual) {
                if ($post['status'] != \Entity\BaseModerable::STATUS_PENDING) {
                    throw new \Exception("Post with id = {$post['id']} is locked for automatic approval");
                }
                $post['updated_by'] = \Entity\LwModerable::UPDATED_BY_LW;
             } else {
                 $post['updated_by'] = \Entity\LwModerable::UPDATED_BY_ADMIN;
            }
            $this->update($post);
        } catch (\Exception $e) {
            App::$inst->log->error($e);
            return false;
        }
        return true;
    }

    /**
     * @todo
     * @param unknown $mod_id
     * @param bool|string $manual
     * @return boolean
     */
    public function rejectEntry($mod_id, $manual = false)
    {
        try {
            $key = is_int($mod_id) ? 'id' : 'lw_mod_id';
            $post = $this->findOneBy(array($key => $mod_id));
            if (!$post) {
                throw new \Exception(__METHOD__ . ': Cannot find entry with lw_mod_id' . $mod_id);
            }
            if (!$manual) {
                if ($post['status'] != \Entity\BaseModerable::STATUS_PENDING) {
                    throw new \Exception("Post with id = {$post['id']} is locked for automatic rejection");
                }
                $post['status'] = \Entity\BaseModerable::STATUS_REJECTED;
            } else {
                $post['status'] = \Entity\BaseModerable::STATUS_MANUAL_REJECTED;
            }
            $post['updated_by'] = \Entity\Lw::UPDATED_BY_LW;
            $this->update($post);
        } catch (\Exception $e) {
            App::$inst->log->info($e);
            return false;
        }
    
        return true;
    }
}
