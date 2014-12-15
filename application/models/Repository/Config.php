<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

class Config extends Base
{
    const CACHE_ID = 'configs';

    /**
     * get config by key
     *
     * @param  $name config key
     * @param string $default default value
     * @return string
     */
    function getValueByName($name, $default = '')
    {
      $records = $this->getConfigs();
      foreach ($records as &$config) {
        if ($config->getName() == $name) {
          return (string)$config->getValue();
        }
      }
      return $default;
    }

    /**
     * get all records
     *
     * @return array
     */
    function getConfigs()
    {
      return $this->createQueryBuilder('c')
          ->getQuery()
          ->useResultCache(true, null, self::CACHE_ID)
          ->getResult();
    }

    /**
     * update multiple config params
     *
     * @param array|null $params
     * @return void
     */
    function updateParams(array $params = null)
    {
      if ($params == null) {
        return;
      }
      $records = $this->getConfigs();
      foreach ($params as $name => &$value) {
        $found = null;
        foreach ($records as &$config) {
          if ($config->getName() == $name) {
            $found = $config;
            break;
          }
        }

        if (!$found) {
          $found = new $this->_entityName();
          $found->setName($name);
        }
        if ($found->getValue() != $value) {
          $found->setValue($value);
          $this->getEntityManager()->persist($found);
        }
      }
      $this->getEntityManager()->flush();

      $this->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete(self::CACHE_ID);
    }

    /**
     * @param string $value   time, example "2012-06-01T09:15:02.000-05:00"
     **/
    public function updateLastProcessedTimePoint($value)
    {
        $this->updateParams(array('lastProcessedTimePoint' => strtotime($value)));
    }

    public function getLastProcessedTimePoint()
    {
        return $this->getValueByName('lastProcessedTimePoint');
    }
}
