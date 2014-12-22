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

 	/**
     * check if user is a winner
     *
     * @param string $id
     * @return boolean
     */
    static function checkWinner($user)
    {
    	die("NOT USED");
    	$is_winner = rand(0,1);
	    $em = \DB::em();
	    $query = $em->createQuery("SELECT gift FROM Entity\Gift gift where gift.winning_time <= :time_to_check and gift.user is NULL");
	    $query->setMaxResults(2);
	    $query->setParameters(array(
	       	'time_to_check' => $user['created']
	    ));
	    $gifts_available = $query->getResult();

	    if ($gifts_available && count($gifts_available) > 0) {
	    	foreach ($gifts_available as $gift) {
	    		$em->beginTransaction();
				try {
					$gift_free = $em->find('Entity\Gift', $gift['id'], \Doctrine\DBAL\LockMode::PESSIMISTIC_WRITE );
					if (!$gift_free['user']) {
						$gift_free['user'] = $user;
						$em->persist($gift_free);
						$repoGift = \DB::gift();
						sleep(5);

						$repoGift->update($gift_free);

						// Check rase conditions
					    $query = $em->createQuery("SELECT gift FROM Entity\Gift gift where gift.id = :gift_id");
					    $query->setMaxResults(1);
					    $query->setParameters(array(
	   			    		'gift_id' => $gift_free['id']
	    				));	    				
	    				$gift_check = $query->getResult();
	    				
	    				//die(var_dump($gift_check[0]['user']));
	    				if ($gift_check && array_key_exists('user' , $gift_check)) {
	    					//Another process has already won this gift, while we were thinking, continue...
	    					continue;
	    					$em->rollback();
	    				}

						sleep(5);
						//$repoGift->update($gift_free);				
						$em->flush();
						$em->commit();
						break;
					}
				}
				catch (\Exception $e){
					$em->rollback();
					//die("EXCEPTION!!!!!!!!!!!!");
					die($e->getMessage());
				}
			}
	    }
	}
}
