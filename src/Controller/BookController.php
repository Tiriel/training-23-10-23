<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookRepository $repository): Response
    {
        $books = $repository->findBy([], ['releasedAt' => 'DESC'], 9);

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::index',
            'books' => $books,
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
    public function new(EntityManagerInterface $manager): Response
    {
        $book = (new Book())
            ->setTitle('1984')
            ->setAuthor('G.Orwell')
            ->setReleasedAt(new \DateTimeImmutable('01-01-1951'))
            ->setIsbn('xxxxxx')
            ->setPlot('This book is too real')
            ->setCover('http://...')
            ;

        $comment = (new Comment())
            ->setName('Awesome!')
            ->setEmail('me@me.com')
            ->setContent('This book is awesome')
            ->setCreatedAt(new \DateTimeImmutable());

        $book->addComment($comment);

        $manager->persist($book);
        $manager->flush();

        return $this->redirectToRoute('app_book_show', [
            'id' => $book->getId()
        ]);
    }
}
