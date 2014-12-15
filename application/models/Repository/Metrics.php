<?php
namespace Repository;

use Repository\Base;

class Metrics
{

    static function __callStatic($name, $arguments)
    {
        return static::getGoal($name, $arguments[0]['startTime'], $arguments[0]['endTime']);
    }

    static function getGoal($criteria, $startTime, $endTime)
    {
        $options = array(
            'g' => false,
            'u' => false
        );
        $parts = explode(';', $criteria);
        foreach ($parts as $part) {
            @list ($key, $value) = explode(':', $part, 2);
            $options = array_replace($options, array(
                $key => $value
            ));
        }

        $query = \DB::em()->createQueryBuilder()
            ->select('m.created, SUM(m.countTotal) as total, SUM(m.countUnique) as unique')
            ->from('Entity\Metrics', 'm')
            ->where('m.name = :name')
            ->andWhere('m.created >= :startTime')
            ->andWhere('m.created <= :endTime')
            ->orderBy('m.created')
            ->groupBy('m.created')
            ->setParameter('name', $options['g'])
            ->setParameter('startTime', $startTime)
            ->setParameter('endTime', $endTime);

        $result = $query->getQuery()->getArrayResult();
        $preparedAnswer = array();
        foreach ($result as $r) {
            if ($options['u']) {
                $preparedAnswer[] = array(
                    'startTime' => $r['created']->getTimestamp(),
                    'value' => (int) $r['unique']
                );
            } else {
                $preparedAnswer[] = array(
                    'startTime' => $r['created']->getTimestamp(),
                    'value' => (int) $r['total']
                );
            }
        }

        return $preparedAnswer;
    }
}