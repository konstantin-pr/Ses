<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Copy Messages used by the Copy Manager are stored in this class
 */
class Copy extends Base
{
    /**
     * create new copy
     *
     * @return Entity\Copy
     */
    function createCopy()
    {
        return new $this->_entityName();
    }

    /**
     * get query for translation data
     * @return Doctrine\ORM\Query
     */
    function getTranslationsQuery()
    {
        $query = $this->getEntityManager()
                ->createQuery('SELECT c.messageid, c.message, c.locale FROM Entity\Copy c');

        return $query;
    }

    /**
     * get copies by key
     *
     * @param string $key
     * @return array
     */
    function getByKey($key)
    {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('c.message, c.locale')
                ->from('Entity\Copy', 'c')
                ->where('c.messageid = ?1')
                ->setParameter(1, $key)
                ->getQuery();

        return $query->getArrayResult();
    }

    /**
     * get copies by id
     *
     * @param string $id
     * @return array
     */
    function getById($id)
    {
        $query = $this->getEntityManager()
                ->createQueryBuilder()
                ->select('c')
                ->from('Entity\Copy', 'c')
                ->where('c.messageid IN (SELECT c2.messageid FROM Entity\Copy c2 WHERE c2.id = ?1)')
                ->setParameter(1, $id)
                ->getQuery();

        return $query->getArrayResult();
    }
}