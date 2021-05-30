<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\LignePanier;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method LignePanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method LignePanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method LignePanier[]    findAll()
 * @method LignePanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignePanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LignePanier::class);
    }

    public function updatePanierUser(User $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function create(LignePanier $lignePanier)
    {
        $this->_em->persist($lignePanier);
        $this->_em->flush();
    }
}
