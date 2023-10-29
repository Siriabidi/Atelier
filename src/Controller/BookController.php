<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\AbidiminmaxType;
use App\Form\SirinesearchType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use PharIo\Manifest\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/showBook', name: 'showBook')]
    public function showBook(BookRepository $bookRepository, Request $request ): Response
    {
       $books = $bookRepository->findAll(); 
        //$books=$bookRepository->orderbyAuthor($datainput);
       $form = $this->createForm(SirinesearchType::class);
       $form->handleRequest($request);
       //$books = [];
       if ($form ->isSubmitted()){
        $datainput=$form->get('id')->getData();
       // $books=$bookRepository->serachbyRef($datainput);
       // $books=$bookRepository->affichelivres($datainput);
        // $books=$bookRepository->updateCategory($datainput);
       }
        return $this->render('book/showBook.html.twig', [
            'books' => $books,
            'f' =>$form->createView(),
         
        ]);
  
    }
  
#[Route('/add/{id}', name: 'add')]
public function add(Request $request, ManagerRegistry $managerRegistry): Response {
    $x = $managerRegistry->getManager();
  

    $book = new Book();
  
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $x->persist($book);
        $x->flush();

        return $this->redirectToRoute('showBook');
    }

    return $this->renderForm('Book/add.html.twig', [
        'f' => $form
    ]);
}
#[Route('/edit', name: 'edit')]

public function editbook(Request $request, ManagerRegistry $manager, BookRepository $bookRepository, $Id): Response
{
    $em=$manager->getManager();
    $book=$bookRepository->find($Id);
   // var_dump($dataid).die() ;
    $form=$this->createForm(BookType::class,$book) ;
    $form->handleRequest($request) ; 

    if($form->isSubmitted() && $form->isValid()){       
        return $this->redirectToRoute('showBook') ;

    }

    return $this->renderForm('book/edit.html.twig', [
        'f' => $form,
    ]);
}
    #[Route('/delete/{id}', name: 'delete')]
    public function delete($id, ManagerRegistry $managerRegistry,BookRepository $bookRepository): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $bookRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirectToRoute('showBook');
    }
    #[Route('/showo', name: 'showo')]
    public function showo(BookRepository $bookRepository): Response
    {
       $books = $bookRepository->findAll(); 
        return $this->render('book/Ola.html.twig', [
            'books' => $books,
         
        ]);
    
}}
