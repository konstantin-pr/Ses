<?php
namespace Repository;

abstract class BaseModerable extends Base
{

    protected $_itemCountPerPage = 6;

    protected $_pageRange = 10;

    /**
     * List of fields, which can be used to search with wildcard "%"
     *
     * @var array
     */
    protected $_searchableFields = array();

    abstract function getSearchableFields();

    /**
     * Get query for moderating entries in Admin
     *
     * @param null $params
     * @param bool $withDeleted
     * @internal param array|string $filters Will be added as WHERE part*            Will be added as WHERE part
     * @return Doctrine\ORM\Query
     */
    function getModerationQueryBuilder($params = null, $withDeleted = false)
    {

        $qb = $this->getEntityManager()
            ->createQueryBuilder('moderation_' . $this->getEntityName())
            ->select('m')
            ->from($this->_entityName, 'm');

        if (!empty($params['search'])) {
            $searchValue = trim($params['search']);
            $searchExpression = array();
            $searchableFields = $this->getSearchableFields();
            foreach ($searchableFields as $field) {
                $searchExpression[] = $qb->expr()->like('m.' . $field, ':search');
            }

            $qb->andWhere(call_user_func_array(array(
                $qb->expr(),
                'orx'
            ), $searchExpression));
            $qb->setParameter('search', '%' . $searchValue . '%');
        }
        if (! $withDeleted && $this->getClassMetadata()->hasField('deleted')) {
            $qb->andWhere('m.deleted is null');
        }
        if (isset($params['filter']) && $params['filter'] != '') {
            $qb->andWhere('m.status = :status')
            ->setParameter('status', $params['filter']);
        }
        if (! empty($params['sort'])) {
            $qb->orderBy('m.' . $params['sort'], 'ASC');
        }

        return $qb;
    }

    /**
     * return Zend_Paginator
     */
    function getPaginator($params)
    {
        require_once APPLICATION_PATH .'/../library/Stuzo/library/ZendX/Doctrine2/Paginator.php'; // Fatal error: Class 'ZendX\Doctrine2\Paginator' not found 
        $adapter = new \ZendX\Doctrine2\Paginator($this->getModerationQueryBuilder($params));

        $zend_paginator = new \Zend_Paginator($adapter);
        $zend_paginator->setItemCountPerPage($this->_itemCountPerPage)->setCurrentPageNumber(isset($params['page']) ? $params['page'] : 1);

        return $zend_paginator;
    }

    function moderate(\Entity\BaseModerable $entity, $status)
    {
        switch ($status) {
            case $entity::STATUS_PENDING:
                $this->pending($entity);
                break;
            case $entity::STATUS_APPROVED:
                $this->approve($entity);
                break;
            case $entity::STATUS_REJECTED:
                $this->reject($entity);
                break;
        }
    }

    function reject(\Entity\BaseModerable $entity)
    {
        if ($entity['status'] == $entity::STATUS_APPROVED) {
            throw new \LogicException('Couldn\'t reject an approved entry, you might have move it to pending first.');
        }
        $entity['status'] = $entity::STATUS_REJECTED;
        $this->getEntityManager()->flush($entity);

        return $this;
    }

    function approve(\Entity\BaseModerable $entity)
    {
        if ($entity['status'] == $entity::STATUS_REJECTED) {
            throw new \LogicException('Couldn\'t approve a rejected entry, you might have move it to pending first.');
        }
        $entity['status'] = $entity::STATUS_APPROVED;
        $this->getEntityManager()->flush($entity);

        return $this;
    }

    function pending(\Entity\BaseModerable $entity)
    {
        $entity['status'] = $entity::STATUS_PENDING;

        $this->getEntityManager()->flush($entity);

        return $this;
    }

    function update($entity, $values = array())
    {
        foreach ($values as $key => $value) {
            $entity[$key] = $value;
        }
        return parent::update($entity);
    }
}
