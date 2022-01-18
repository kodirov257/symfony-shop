<?php

namespace App\Repository\Shop;

use App\Entity\Shop\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

///**
// * @method Type|null find($id, $lockMode = null, $lockVersion = null)
// * @method Type|null findOneBy(array $criteria, array $orderBy = null)
// * @method Type[]    findAll()
// * @method Type[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
// */
class TypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }

    public function get(int $id): Type
    {
        /** @var Type $type */
        if (!$type = $this->find($id)) {
            throw new EntityNotFoundException('Type is not found.');
        }

        return $type;
    }

    public function add(Type $type): void
    {
        $this->_em->persist($type);
    }

    public function remove(Type $type): void
    {
        $this->_em->remove($type);
    }

    public function flush(): void
    {
        $this->_em->flush();
    }

    // /**
    //  * @return Type[] Returns an array of Type objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Type
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
