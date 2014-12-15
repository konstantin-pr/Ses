<?php

namespace Application;

class App
{
    /**
     * @var \Slim\Slim $inst;
     * @var Array $inst->config
     * @var \Doctrine\ORM\EntityManager $inst->em
     * @var \Doctrine\Common\Cache\MemcacheCache $inst->cache
     * @var \Doctrine\Common\Cache\RedisCache $inst->cache
     * @var \Doctrine\Common\Cache\ApcCache $inst->cache
     * @var \Doctrine\Common\Cache\ArrayCache $inst->cache
     * @var \Library\Logger $inst->log
     * @var \Facebook $inst->fb
     * @var string $inst->locale
     * @var string $inst->pageId facebook page id
     * @var string $inst->pageUrl facebook page url
     * @var string $inst->appPageUrl facebook app page url
     * @var string $inst->memcache memcahe obj
     * @var string $inst->redis redis obj
     */
    static public $inst;
}