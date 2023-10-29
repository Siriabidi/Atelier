<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function serachbyref($Ref){
        return $this ->createQueryBuilder('b')
        ->where(('b.Ref= :Ref'))
        ->setParameter('Ref',$Ref)
        ->getQuery()
        ->getResult();
    
    }
    public function orderbyAuthor(){
        return $this->createQueryBuilder('b')
        ->OrderBy('b.author','Asc')
        ->getQuery()
        ->getResult();
        }
     
        public function affichelivres()
        {
           
            // $em = $this ->getEntityManager(); 
            //     return $em->createQuery('SELECT b from App\Entity\Book a where a.nbrlivre > ?35 and b.publicationDate  ')
            //     ->setParameters(['year' => $publicationYear, 'minBooks' => $minNumberOfBooks])
            //     ->getQuery()
            //     ->getResult();
            $em = $this->getEntityManager();

            $query = $em->createQuery('
                SELECT b
                FROM App\Entity\Book b
                JOIN b.Author a
                WHERE b.publicationDate < :year
                AND a.nbbook > :minBooks
            ')
            ->setParameters(['year' => 2023, 'minBooks' => 35]);
        
            return $query->getResult();
        }
        public function updateCategory()
        {
            $qb = $this->createQueryBuilder('b')
            ->join('b.Author', 'a')
            ->where('a.Username = :authorName')
            ->setParameter('authorName', 'William Shakespeare')
            ->getQuery();
        $books = $qb->getResult();
        foreach ($books as $book) {
            $book->setCategory('Romance');
        }
        $this->_em->flush();
    }


    public function getSum()
    {
        return $this->createQueryBuilder('b')
            ->select('SUM(b.nbbook) as total')
            ->where('b.category = :category')
            ->setParameter('category', 'Science Fiction')
            ->getQuery()
            ->getSingleScalarResult();
    }
        






//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
