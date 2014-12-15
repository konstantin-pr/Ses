<?php
namespace Application;
final class DB
{

    protected static $instances = array();

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    static function em()
    {
        return class_exists('\Application\App') ? App::$inst->em : \Zend_Registry::get('entityManager');
    }

    /**
     *
     * @return \Doctrine\Common\Cache\AbstractCache
     */
    static function cache()
    {
        return self::em()->getConfiguration()->getResultCacheImpl();
    }

    /**
     *
     * @return \Doctrine\DBAL\Connection
     */
    static function pdo()
    {
        return self::em()->getConnection();
    }

    static function __callStatic($name, $arguments)
    {
        if (! isset(self::$instances[$name])) {
            self::$instances[$name] = self::em()->getRepository('Entity\\' . ucfirst($name));
        }

        return self::$instances[$name];
    }
}
class_alias('Application\DB','DB');
