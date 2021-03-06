<?php

namespace AppBundle\Repository;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Liste des clients par ordre alphabetique croissant
     */
    public function findList($statut = null)
    {
        if ($statut){
            return $this->createQueryBuilder('c')
                        ->addSelect('a')
                        ->leftJoin('c.assurance', 'a')
                        ->where('c.statut = 1')
                        ->orderBy('c.nom', 'ASC')
                        //->getQuery()->getResult()
                        ;
        } else{
            return $this->createQueryBuilder('c')
                        ->addSelect('a')
                        ->leftJoin('c.assurance', 'a')
                        ->orderBy('c.nom', 'ASC')
                        ->getQuery()->getResult();
        }
    }

    /**
     * Le client concerné par la requête
     */
    public function findClient($slug)
    {
        return $this->createQueryBuilder('c')
                    ->where('c.slug = :client')
                    ->setParameter('client', $slug)
            ;
    }

    /**
     * Calcul du nombre total de client
     * use DefaultController::index
     */
    public function findNombre()
    {
        return $this->createQueryBuilder('c')
                    ->select('count(c.id)')
                    ->where('c.statut = 1')
                    ->getQuery()->getSingleScalarResult()
            ;
    }
}
