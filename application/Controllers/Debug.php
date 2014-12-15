<?php
namespace Application\Controllers;
use Application\DB;
use Application\App;

class Debug
{
    public function __construct()
    {
        $this->testAccess();
    }
    
    protected function testAccess()
    {
        if (IS_PRODUCTION or IS_SAVVIS) {
            echo 'Access denied';
            exit();
        }
    }

    function userRemove()
    {
        $this->testAccess();
        \DB::em()->createQuery("DELETE FROM \Entity\User a WHERE a.facebook_user_id = " . FB::getUser())->execute();
        die(json_encode(array('status'=>'ok')));
        
    }
    
    /**
     * @todo
     */
    function userDelete() {
        $success = true;
        $message = 'User Removed successfully';
        try {
            DB::em()->createQuery("DELETE FROM \Entity\User a WHERE a.facebook_user_id = " . App::$inst->fb->getUser())->execute();
        } catch(\Exception $e) {
            $success = false;
            $message = $e->getMessage() . $e->getTraceAsString();
        }
        $this->jsonResponse($success, $message);
    }
    
    /**
     * @todo
     */
    function cacheClear() {
        $cacheIds = DB::em()->getConfiguration()->getMetadataCacheImpl()->getStats();
        $success = true;
        try {
            if (App::$inst->request->post('clear')) {
                DB::em()->getConfiguration()->getMetadataCacheImpl()->flushAll();
                $message = "Cleared";
            } elseif(!empty($cacheIds)) {
                $message = $cacheIds;
            } else {
                $message = "Nothing to clear. getStats() returns null";
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage() . $e->getTraceAsString();
        }
        $this->jsonResponse($success, $message);
    }
    
    /**
     * @todo
     */
    public function dbUpdate() {
        $success = true;
        try {
            $dummy = DB::em()->getConfiguration()->getMetadataDriverImpl();
            $dummy->addPaths(array(
                APPLICATION_PATH . '/models',
            ));
            $metadatas = DB::em()->getMetadataFactory()->getAllMetadata();
            if (!empty($metadatas)) {
                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(DB::em());
                $sqls = $schemaTool->getUpdateSchemaSql($metadatas, true);
                if (!empty($sqls)) {
                    if (App::$inst->request->post('update')) {
                        $schemaTool->updateSchema($metadatas, true);
                        $message = 'Database schema updated successfully!';
                    } else {
                        $message = 'List of updates:<br/>';
                        $message .= implode(';' . PHP_EOL, $sqls) . ';';
                    }
                } else {
                    $message = 'SQLs are empty. Database schema is up to date';
                }
            } else {
                $message = 'Database schema is up to date';
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage() . $e->getTraceAsString();
        }
        $this->jsonResponse($success, $message);
    }
    
    private function jsonResponse($success, $message){
        $data['success'] = $success;
        $data['message'] = $message;
        echo json_encode($data);
    }
}
