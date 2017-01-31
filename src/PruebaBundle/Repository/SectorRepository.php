<?php

namespace PruebaBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SectorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SectorRepository extends EntityRepository
{
    public function comprobarSectorVacio()
    {
        $query = $this->getEntityManager()->createQuery('SELECT COUNT(s.id) FROM PruebaBundle:Sector s');
        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}