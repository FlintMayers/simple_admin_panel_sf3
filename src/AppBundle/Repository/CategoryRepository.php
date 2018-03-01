<?php

namespace AppBundle\Repository;

/**
 * CategoryRepository
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Get all categories that user belongs to
     *
     * @param $id
     * @return array
     */
    public function findCategoriesByUserId($id)
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.users', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $id)
            ->getQuery()->getResult();
        return $qb;
    }

    /**
     * Get all categories that user does not belong to
     *
     * @param $id
     * @return array
     */
    public function findCategoriesUserNotInByUserId($id)
    {
        $qb = $this->createQueryBuilder('c')
            ->where(':user_id NOT MEMBER OF c.users')
            ->setParameter('user_id', $id)
            ->getQuery()->getResult();
        return $qb;
    }
}
