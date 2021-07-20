<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Reponse;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\BrowserKit\Request;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEmailController extends AbstractController
{

    private $adminUrlGenerator;
    private $entityManager;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, EntityManagerInterface $entityManager)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/email/form", name="email_form")
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // $url = $this->adminUrlGenerator->setRoute("email_form")->generateUrl();

        // $task = new User();
        $this->monthFlag = false;
        $lastLogin = $this->entityManager->getRepository(\App\Entity\User::class)->findOneBy(['email' => $request->attributes->get('email')])->getLastLogin();
        if ($lastLogin !== null) {
            $now = new \DateTime;
            $interval = new \DateInterval('P1M');
            $now->sub($interval);
            if ($now >= $lastLogin) {
                $this->monthFlag = true;
            }
        }

        $form = $this->createFormBuilder()
            ->add('EmailType', ChoiceType::class, [
                'choices' => [
                    '    ' => 0,
                    'Logged In During Last Month' => 1,
                    'Didn\'t Log In During Last Month' => 2,
                ],
                'choice_attr' => function ($val, $key, $index) {
                    dump($val, $key, $index, $this->monthFlag);
                    $disabled = false;
                    if ($this->monthFlag === true) {
                        if ($val === 1) {
                            $disabled = true;
                        }
                    } else {
                        if ($val === 2) {
                            $disabled = true;
                        }
                    }
                    return $disabled ? ['disabled' => 'disabled'] : [];
                }
            ])
            ->add('Send', SubmitType::class, [
                'label' => 'Send Email'
            ])->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            switch ($request->get('form')['EmailType']) {
                case 1:
                    $message = (new TemplatedEmail())
                        ->from('send@example.com')
                        ->to($request->attributes->get('email'))
                        ->subject('Logged in last month')
                        ->htmlTemplate('admin_email/email_logged.html.twig');
                    break;

                case 2:
                    $message = (new TemplatedEmail())
                        ->from('send@example.com')
                        ->to($request->attributes->get('email'))
                        ->subject('Didn\'t log in last month')
                        ->htmlTemplate('admin_email/email_not_logget.html.twig');
                    break;

                default:
                    # code...
                    break;
            }
            $mailer->send($message);
            return $this->redirectToRoute('admin');
        }
        $this->addFlash('success', 'Email succesfully sent!');
        return $this->render('admin_email/index.html.twig', ['form' => $form->createView()]);
    }
    public function new(Request $request): Response
    {
        // just setup a fresh $task object (remove the example data)
        $task = new User();

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();

            return $this->redirectToRoute('task_success');
        }

        return $this->render('task/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
