<?php
namespace Repository;

use Doctrine\ORM\EntityRepository;

class Base extends EntityRepository
{

    public function newEntity()
    {
        return new $this->_entityName();
    }

    public function update($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /*
     * Delete entry by id
     */
    public function delete($id)
    {
        $count = $this->getEntityManager()
            ->createQuery('DELETE FROM ' . $this->_entityName . ' d WHERE d.id = :id')
            ->setParameter('id', $id)
            ->execute();

        return $count;
    }

    /*
     * Get Total for selected table
     */
    public function getTotal()
    {
        $count = $this->getEntityManager()
            ->createQuery('SELECT COUNT(t.id) as total FROM ' . $this->_entityName . ' t')
            ->getSingleScalarResult();

        return $count;
    }
}
