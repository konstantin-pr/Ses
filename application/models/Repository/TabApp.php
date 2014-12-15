<?php

namespace Repository;

use Doctrine\ORM\EntityRepository;

/**
 * used to kepp info about tab apps
 */
class TabApp extends Base
{
	const CACHE_ID = 'tabapps';

	/**
	 * create TabApp entity
	 *
	 * @param  $id
	 * @param  $name
	 * @return TabApp
	 */
	function createApp($id, $name)
	{
		$app = new $this->_entityName();
		$app->setId($id);
		$app->setName($name);
		$this->getEntityManager()->persist($app);
		$this->getEntityManager()->flush();

		$this->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete(self::CACHE_ID);

		return $app;
	}

	/**
	 * get all active tabapps
	 *
	 * @return array
	 */
	function getLayout()
	{
		return $this->createQueryBuilder('app')
			->where('app.zone IS NOT NULL')
			->getQuery()
			->useResultCache(true, null, self::CACHE_ID)
			->getResult();
	}

	/**
	 * update tabapp by zone
	 *
	 * @param   string  $zone
	 * @param   string  $value
	 * @return  integer
	 */
	function updateByZone($zone, $value = null)
	{
		$result = $this->getEntityManager()
			->createQueryBuilder()
			->update('Entity\TabApp', 'app')
			->set('app.zone', '?1')
			->where('app.zone = ?2')
			->setParameter(1, $value)
			->setParameter(2, $zone)
			->getQuery()
			->getSingleScalarResult();

		$this->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete(self::CACHE_ID);

		return $result;
	}

	/**
	 * update tabapp by id
	 *
	 * @param   string   $id
	 * @param   string   $value
	 * @return  integer
	 */
	function updateById($id, $value)
	{
		$result = $this->getEntityManager()
			->createQueryBuilder()
			->update('Entity\TabApp', 'app')
			->set('app.zone', '?1')
			->where('app.id = ?2')
			->setParameter(1, $value)
			->setParameter(2, $id)
			->getQuery()
			->getSingleScalarResult();

		$this->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete(self::CACHE_ID);

		return $result;
	}

	/*
	 * Method let us possibility generate app Id by name where do we need it
	 *
	 * @param string $name
	 *
	 * @return string id
	 */
	static function getnerateIdByName($name)
	{
		return md5(strtolower($name));
	}
}