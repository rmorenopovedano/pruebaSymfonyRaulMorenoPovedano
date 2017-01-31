<?php

namespace PruebaBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EmpresaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmpresaRepository extends EntityRepository
{
    public function cargarSectorEmpresa($nombre = '', $sector)
    {
        $qb = $this->getEntityManager()
            ->createQueryBuilder();

        $qb->select('e, s')
            ->from('PruebaBundle:Empresa', 'e')
            ->join('e.sector', 's')
            ->where('e.nombre LIKE :nombre')
            ->setParameter('nombre', '%'.$nombre.'%');

        if($sector){
            $qb->andWhere('e.sector = :idSector')
                ->setParameter('idSector', $sector);
        }

        ;
        return $qb;
    }
}