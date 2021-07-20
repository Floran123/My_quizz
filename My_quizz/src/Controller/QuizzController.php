<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Categorie;
use App\Form\Question1Type;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quizz")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="quizz_index", methods={"GET"})
     */
    public function index(): Response
    {
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findAll();
        $categories = $this->getDoctrine()
        ->getRepository(Categorie::class)
        ->findBy(['name' => "test4"]);

        return $this->render('quizz/index.html.twig', [
            'questions' => $questions,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/new", name="quizz_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $question = new Question();
        // $categorie = new Categorie();
        $form = $this->createForm(Question1Type::class, $question);
        $form->handleRequest($request);

        // dd($_GET["name"]);

        $categories = $this->getDoctrine()
        ->getRepository(Categorie::class)
        ->findBy(['name' => $_GET['name']]);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setIdCategorie($_POST["question1"]["idCategorie"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            // $entityManager->persist($categorie);
            // dd($_POST);
            $entityManager->flush();

            return $this->redirectToRoute('quizz_index');
        }

        return $this->render('quizz/new.html.twig', [
            'question' => $question,
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quizz_show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        return $this->render('quizz/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quizz_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(Question1Type::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quizz_index');
        }

        return $this->render('quizz/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quizz_delete", methods={"POST"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quizz_index');
    }
}
