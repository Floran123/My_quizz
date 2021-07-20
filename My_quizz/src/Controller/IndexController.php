<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Categorie;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(\Swift_Mailer $mailer): Response
    {

        $categories = $this->getDoctrine()
            ->getRepository(Categorie::class)
            ->findAll();

        // $message = (new \Swift_Message('Hello Email'))
        //     ->setFrom('send@example.com')
        //     ->setTo('recipient@example.com')
        //     ->setBody(
        //         $this->renderView(
        //             "templates/emails/registration.html.twig",
        //             ['name' => $name]
        //         ),
        //         'text/html'
        //     );

        // $mailer->send($message);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'categories' => $categories,
        ]);
    }
    /**
     * @Route("/user/{id}", name="user_show")
     */
    // public function displayProfile(UserInterface $user): Response
    // {
    //     $profile = $this->getDoctrine()
    //         ->getRepository(User::class)
    //         ->find($user->getId());

    //     if (!$profile) {
    //         throw $this->createNotFoundException(
    //             'No user found for id ' . $user->getId()
    //         );
    //     }

    //     return new Response('User: ' . $profile->getUsername());

    //     // or render a template
    //     // in the template, print things with {{ product.name }}
    //     // return $this->render('product/show.html.twig', ['product' => $product]);
    // }
}
