<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    // Attributs
    protected TokenStorageInterface $tokenStorageInterface;

    public function __construct(ManagerRegistry $registry, TokenStorageInterface $tokenStorageInterface)
    {
        parent::__construct($registry, Address::class);
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    public function getQueryBuilderfindAllByUser(): QueryBuilder
    {
        $user = $this->tokenStorageInterface->getToken()->getUser();

        return $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.status', 'ASC');
    }
}
