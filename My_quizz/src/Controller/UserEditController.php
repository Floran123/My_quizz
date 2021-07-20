<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEditController extends AbstractController
{

    private $verifyEmailHelper;
    private $mailer;

    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
    }

    /**
     * @Route("user/edit/{id}", name="user_edit", requirements={"id":"\d+"})
     */
    public function index(User $user, Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $userIs = $this->get('security.token_storage')->getToken()->getUser();
        if (intval($request->attributes->get('id')) !== $userIs->getId()) {
            return $this->redirectToRoute('index');
        }
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('save', SubmitType::class, ['label' => 'Edit'])
            ->getForm();
        $userCopyEmail = $userIs->getEmail();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $userInfo = $form->getData();

            if ($userInfo->getEmail() !== $userCopyEmail) {
                $userInfo->setIsVerified(false);

                $signatureComponents = $this->verifyEmailHelper->generateSignature(
                    'app_verify_email',
                    $userInfo->getId(),
                    $userInfo->getEmail()
                );

                $email = new TemplatedEmail();
                $email->to($userInfo->getEmail());
                $email->from('from@example.com');
                $email->subject('Email Verification');
                $email->htmlTemplate('registration/confirmation_email.html.twig');
                $email->context(['signedUrl' => $signatureComponents->getSignedUrl()]);

                $this->mailer->send($email);
            }

            $userInfo->setPassword($encoder->encodePassword($userInfo, $userInfo->getPassword()));

            $em->persist($userInfo);
            $em->flush();
            $this->addFlash('success', 'Profile successfully edited!');
            return $this->redirectToRoute('index');
        }

        return $this->render('user_edit/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
