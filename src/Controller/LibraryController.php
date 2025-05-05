<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig', [
            'controller_name' => 'LibraryController',
        ]);
    }

    #[Route('/library/create', name: 'library_create', methods: ['GET', 'POST'])]
    public function createBook(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        if ($request->isMethod('POST')) {
            $entityManager = $doctrine->getManager();

            $book = new Book();
            $book->setTitle($request->request->get('title'));
            $book->setIsbn($request->request->get('isbn'));
            $book->setAuthor($request->request->get('author'));
            $book->setImage($request->request->get('image'));

            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('library');
        }

        return $this->render('library/create.html.twig');
    }

    #[Route('/library/show', name: 'library_show_all')]
    public function showAllBooks(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository
            ->findAll();

        return $this->render('library/show.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/library/book/{id}', name: 'library_show_one')]
    public function showOneBook(
        BookRepository $bookRepository,
        int $id
    ): Response {
        $book = $bookRepository->find($id);

        return $this->render('library/show_one.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/edit/{id}', name: 'library_edit', methods: ['GET', 'POST'])]
    public function editBook(
        int $id,
        Request $request,
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository->find($id);

        if ($request->isMethod('POST')) {
            $book->setTitle($request->request->get('title'));
            $book->setIsbn($request->request->get('isbn'));
            $book->setAuthor($request->request->get('author'));
            $book->setImage($request->request->get('image'));

            $entityManager = $doctrine->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('library_show_one', ['id' => $book->getId()]);
        }

        return $this->render('library/edit.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/library/delete/{id}', name: 'library_delete', methods: ['POST'])]
    public function deleteBook(
        int $id,
        ManagerRegistry $doctrine,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                "Ajdå, vi hittade ingen bok med id: $id"
            );
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library_show_all');
    }
}
