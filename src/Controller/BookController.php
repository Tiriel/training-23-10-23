<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index')]
    public function index(BookRepository $repository): Response
    {
        $books = $repository->findBy([], ['releasedAt' => 'DESC'], 10);

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::index',
            'books' => $books,
        ]);
    }

    #[Route('/{!id<\d+>?2}',
        name: 'app_book_show',
        requirements: ['id' => '\d+'],
        defaults: ['id' => 2],
        methods: ['GET', 'POST'],
    //condition: "request.headers.get('x-custom-header') == 'foo'"
    )]
    public function show(BookRepository $repository, int $id = 1): Response
    {
        $book = $repository->find($id);

        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController::show - id: '.$id,
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    public function new(EntityManagerInterface $manager): Response
    {
        $book = (new Book())
            ->setTitle('1984')
            ->setAuthor('G.Orwell')
            ->setReleasedAt(new \DateTimeImmutable('01-01-1951'))
            ->setIsbn('xxxxxx')
            ->setPlot('This book is too real')
            ;

        $manager->persist($book);
        $manager->flush();

        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController::new - id :'. $book->getId(),
        ]);
    }
}
