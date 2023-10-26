<?php

namespace App\Controller;

use App\Book\BookManager;
use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Security\Voter\BookEditVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookManager $manager): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $manager->getBookList(),
        ]);
    }

    #[Route('/{!id}',
        name: 'app_book_show',
        methods: ['GET', 'POST'],
    //condition: "request.headers.get('x-custom-header') == 'foo'"
    )]
    public function show(BookRepository $repository, Uuid $id): Response
    {
        $book = $repository->find($id);

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/new', name: 'app_book_new', priority: 1)]
    #[Route('/{id}/edit', name: 'app_book_edit')]
    public function save(?Book $book, Request $request, EntityManagerInterface $manager): Response
    {
        if ($book) {
            $this->denyAccessUnlessGranted(BookEditVoter::EDIT, $book);
        }

        $book ??= new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('app_book_show', [
                'id' => $book->getId()
            ]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
