<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Str;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Security\AppCustomAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    private $em;
    private $mailer;

    public function __construct(EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, Str $str): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $token = $str->_token(60);
            $user->setToken($token);
            $user->setTokenExpiredAt(new \DateTime());

            $this->em->persist($user);
            $this->em->flush();

            $message = (new TemplatedEmail())
                        ->from(new Address("noreply@allocar.mg", "AlloCar"))
                        ->to($user->getEmail())
                        ->subject('Merci de confirmer votre inscription')
                        ->htmlTemplate('emails/register.mail.twig')
                        ->context([
                            'user' => $user,
                        ])
            ;

            $this->mailer->send($message);

            $emailArray = explode('@',$user->getEmail());
            $email = substr($emailArray[0],0,3) . "...@" . $emailArray[1];
            
            $message = "Vous avez reçu un message à l'adresse $email contenant un lien de confirmation de votre inscription.";
            $this->addFlash("success", $message);

            return $this->redirectToRoute('page');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function confirm(User $user, $token, Request $req, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator)
    {
        if($user->getToken() != $token)
            throw $this->createNotFoundException();
        $user->setIsActive(true);
        $user->setToken(null);
        $user->setTokenExpiredAt(null);

        $this->em->persist($user);
        $this->em->flush();

        $this->addFlash('success', 'Votre compte a été activé');

        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $req,
            $authenticator,
            'main'
        );
    }
}
