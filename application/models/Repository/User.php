<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;
use \Entity\User as EUser;
use \Entity\GameSettings as EGS;
use \Entity\GiftCounter as EGC;

/**
 * used to kepp info about tab apps
 */
class User extends Base
{

	 /**
     * get copies by id
     *
     * @param integer $id
     * @return array
     */
    function getById($id)
    {
    	$query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('Entity\User', 'u')
            ->where('u.id = ?1)')
            ->setParameter(1, $id)
            ->getQuery();

        return $query->getArrayResult();
    }


	 /**
     * get copies by email
     *
     * @param string $email
     * @return array
     */
    function getByEmail($email)
    {
    	$query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('Entity\User', 'u')
            ->where('u.email = ?1)')
            ->setParameter(1, $email)
            ->getQuery();

        return $query->getArrayResult();
    }

}
