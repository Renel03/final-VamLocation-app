<?php

namespace App\Controller;

use App\Service\Str;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\AppCustomAuthenticator;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\City;
use App\Entity\Post;
use App\Entity\Faq;
use App\Entity\Trademark;
use App\Entity\Model;
use App\Entity\Version;
use App\Entity\User;
use App\Entity\Demand;
use App\Form\RentType;
use App\Service\PostsCollection;
use App\Service\FaqsCollection;
use App\Service\DemandsCollection;

/**
 * @Route("/api/v1",name="api_")
 */
class ApiController extends AbstractFOSRestController
{
    private $em;
    private $encoder;
    private $mailer;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        $this->mailer = $mailer;
    }

    /**
     * @Rest\Get("/user")
     * @Rest\View()
     */
    public function user()
    {
        return $this->json($this->getUser(), 200, [], [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'groups' => ['details'],
        ]);
    }
    
    /**
     * @Rest\Get("/load-demand-temp")
     * @Rest\View()
     */
    public function loadDemandTemp(Request $req)
    {
        $data = [
            'demand' => $req->getSession()->get('demand')
        ];
        return $this->json($data, 200, [], []);
    }

    /**
     * @Rest\Post("/booking/{step<\d+>}")
     * @Rest\View()
     */
    public function booking_save($step, Request $req, GuardAuthenticatorHandler $guardHandler, AppCustomAuthenticator $authenticator, UserPasswordEncoderInterface $passwordEncoder, Str $str)
    {
        $demandTemp = $req->getSession()->get('demand');
        if(!in_array($step, ['1', '2']) || $step == 2 && is_null($demandTemp))
            return $this->json([
                'success' => false,
                'messages' => []
            ], 200, [], []); 
        $errors = [];

        $demand = new Demand();

        if($step == 2){
            $demand->setTripType($demandTemp['tripType']);
            $demand->setNbPers($demandTemp['nbPers']);
            $demand->setCheckInAt($demandTemp['checkInAt']);
            $demand->setCheckOutAt($demandTemp['checkOutAt']);
            if(!is_null($demandTemp['fromCity'])){
                $from = $this->em->getRepository(City::class)->find($demandTemp['fromCity']->getId());
                $demand->setFromCity($from);
            }
            if(!is_null($demandTemp['toCity'])){
                $to = $this->em->getRepository(City::class)->find($demandTemp['toCity']->getId());
                $demand->setToCity($to);
            }
            
            $form_data = $req->request->get('rent');
            if(!empty($form_data)){
                if($form_data['isAuth'] == 0){
                    $user = $this->em   ->getRepository(User::class)
                                        ->findOneBy(['email' => $form_data['email']]);
                }else{
                    $user = $this->getUser();
                }
                if($form_data['isNewUser'] == 1 && $form_data['isAuth'] == 0){
                    if(!is_null($user)){
                        $errors['email'] = "Désolé, cette adresse e-mail est déjà utilisée par un autre compte";
                    }else{
                        $user = new User();
                        $user->setType(0);
                        $user->setLastname($form_data['lastname']);
                        $user->setFirstname($form_data['firstname']);
                        $user->setPhone($form_data['phone']);
                        $user->setEmail($form_data['email']);
                        $user->setPassword(
                            $passwordEncoder->encodePassword(
                                $user,
                                $form_data['plainPassword']
                            )
                        );

                        $token = $str->_token(60);
                        $user->setToken($token);
                        $user->setTokenExpiredAt(new \DateTime());
                    }
                }
                
                if($form_data['isNewUser'] == 0 && $form_data['isAuth'] == 0){
                    if(is_null($user)){
                        $errors['email'] = "L'adresse e-mail n'a pas pu trouver dans nos fichiers";
                    }else{
                        if(!$this->encoder->isPasswordValid($user, $form_data['plainPassword'])){
                            $errors['plainPassword'] = "Le mot de passe n'est pas correct";
                        }
                    }
                }

                if($form_data['tripType3Cities'] != ""){
                    $form_data['tripType3Cities'] = explode(',', $form_data['tripType3Cities']);
                    $req->request->set('rent', $form_data);
                }

                if(empty($errors))
                    $demand->setUser($user);
            }
        }

        $form = $this->createForm(RentType::class, $demand, [
            'step' => $step,
            'csrf_protection' => false
        ]);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid() && empty($errors)){
            if($step == 1){
                $demandTemp['tripType'] = $demand->getTripType();
                $demandTemp['fromCity'] = $demand->getFromCity();
                $demandTemp['toCity'] = $demand->getToCity();
                $demandTemp['checkInAt'] = $demand->getCheckInAt();
                $demandTemp['checkOutAt'] = $demand->getCheckOutAt();
                $demandTemp['nbPers'] = $demand->getNbPers();
                
                $req->getSession()->set('demand', $demandTemp);
                return $this->json([
                    'success' => true
                ], 200, [], []);
            }else{
                $this->em->persist($demand);
                $this->em->flush();

                $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $req,
                    $authenticator,
                    'main'
                );

                if($form->get('isNewUser')->getData() == 1 && $form_data['isAuth'] == 0){
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
                }

                // ad published
                $message = (new TemplatedEmail())
                            ->from(new Address("noreply@allocar.mg", "AlloCar"))
                            ->to($user->getEmail())
                            ->subject('Demande N°#' . $demand->getId())
                            ->htmlTemplate('emails/ad.mail.twig')
                            ->context([
                                'demand' => $demand,
                            ])
                ;

                $this->mailer->send($message);

                if($form->get('isNewUser')->getData() != 1 || $form_data['isAuth'] != 0){
                    $this->addFlash("success", "Votre demande N°#" . $demand->getId() . " a été envoyé aux professionnels.");
                }

                $req->getSession()->remove('demand');

                return $this->json([
                    'success' => true,
                    'messages' => []
                ], 200, [], []);
            }
        }

        foreach($form as $fieldName => $formField) {
            foreach ($formField->getErrors(true) as $error) {
                $errors[$fieldName] = $error->getMessage();
            }
        }
        return $this->json([
            'success' => false,
            'messages' => $errors,
        ], 200, [], []);
    }
    
    /**
     * @Rest\Get("/cities/options")
     * @Rest\View()
     */
    public function cities_options(Request $req)
    {
        $query = $this->em ->getRepository(City::class)
                            ->createQueryBuilder('c')
                            ->select('c.id', 'c.cp', 'c.name')
                            ->orderBy('c.cp', 'ASC')
                            ->getQuery()
                            ->getResult();
        $cities = [];
        foreach($query as $i => $city){
            $cities[$i]['value'] = $city['id'];
            $cities[$i]['label'] = $city['name'] . ' ' . $city['cp'];
        }

        return $this->json($cities, 200, [], []);
    }
    
    /**
     * @Rest\Get("/trademarks/options/{type<\d{1}>}")
     * @Rest\View()
     */
    public function trademark_options($type, Request $req)
    {
        $query = $this->em ->getRepository(Trademark::class)
                            ->createQueryBuilder('t')
                            ->select('t.slug', 't.name')
                            ->where('t.type = :type')
                            ->setParameter('type', $type)
                            ->orderBy('t.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        $trademarks = [];
        foreach($query as $i => $trademark){
            $trademarks[$i]['value'] = $trademark['slug'];
            $trademarks[$i]['label'] = $trademark['name'];
        }

        return $this->json($trademarks, 200, [], []);
    }

    /**
     * @Rest\Get("/models/options/{slug<[a-z0-9-]+>}")
     * @Rest\View()
     */
    public function model_options($slug, Request $req)
    {
        $query = $this->em ->getRepository(Model::class)
                            ->createQueryBuilder('m')
                            ->join('m.trademark', 't')
                            ->select('m.slug', 'm.name')
                            ->orderBy('m.name', 'ASC')
                            ->where('t.slug = :slug')
                            ->setParameter('slug', $slug)
                            ->getQuery()
                            ->getResult();
        $models = [];
        foreach($query as $i => $model){
            $models[$i]['value'] = $model['slug'];
            $models[$i]['label'] = $model['name'];
        }

        return $this->json($models, 200, [], []);
    }
    
    /**
     * @Rest\Get("/trademarks/options")
     * @Rest\View()
     */
    public function trademarks_options(Request $req)
    {
        $trademarks = $this->em ->getRepository(Trademark::class)
                            ->createQueryBuilder('t')
                            ->select('t.slug', 't.name')
                            ->orderBy('t.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        return $this->json($trademarks, 200, [], []);
    }
    
    /**
     * @Rest\Get("/models/options")
     * @Rest\View()
     */
    public function models_options(Request $req)
    {
        $models = $this->em ->getRepository(Model::class)
                            ->createQueryBuilder('m')
                            ->select('m.slug', 'm.name')
                            ->orderBy('m.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        return $this->json($models, 200, [], []);
    }
    
    /**
     * @Rest\Get("/versions/options")
     * @Rest\View()
     */
    public function versions_options(Request $req)
    {
        $versions = $this->em ->getRepository(Version::class)
                            ->createQueryBuilder('v')
                            ->select('v.slug', 'v.name')
                            ->orderBy('v.name', 'ASC')
                            ->getQuery()
                            ->getResult();
        return $this->json($versions, 200, [], []);
    }
    
    /**
     * @Rest\Get("/posts")
     * @Rest\View()
     */
    public function posts(Request $req)
    {
        $query = $req->query->get('search');
        if(is_null($query)) $query= [];
        if(key_exists('type', $query)){
            $type = explode(',',$query['type']);
            $query['type'] = $type;
        }

        if(key_exists('state', $query)){
            $state = explode(',',$query['state']);
            $query['state'] = $state;
        }

        if(key_exists('fuel', $query)){
            $fuel = explode(',',$query['fuel']);
            $query['fuel'] = $fuel;
        }
        
        if(key_exists('trademark', $query)){
            $trademark = explode(',',$query['trademark']);
            $query['trademark'] = $trademark;
        }
        
        if(key_exists('model', $query)){
            $model = explode(',',$query['model']);
            $query['model'] = $model;
        }
        
        if(key_exists('version', $query)){
            $version = explode(',',$query['version']);
            $query['version'] = $version;
        }

        $limit = 24;
        if(!is_null($req->query->get('limit')) && (int) $req->query->get('limit')){
            $limit = $req->query->get('limit');
        }
        
        if(!is_null($req->query->get('page')) && (int) $req->query->get('page')){
            $query['page'] = $req->query->get('page');
        }

        $posts = $this->em->getRepository(Post::class)->search($query, $limit);
        $pagination = new PostsCollection($posts);
        return $this->json($pagination, 200, [], [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'groups' => ['list'],
        ]);
    }
    
    /**
     * @Rest\Get("/demands")
     * @Rest\View()
     */
    public function demands(Request $req)
    {
        $query = $req->query->all();
        if(is_null($query)) $query = [];
        // if(key_exists('type', $query)){
        //     $type = explode(',',$query['type']);
        //     $query['type'] = $type;
        // }

        // if(key_exists('state', $query)){
        //     $state = explode(',',$query['state']);
        //     $query['state'] = $state;
        // }

        // if(key_exists('fuel', $query)){
        //     $fuel = explode(',',$query['fuel']);
        //     $query['fuel'] = $fuel;
        // }
        
        // if(key_exists('trademark', $query)){
        //     $trademark = explode(',',$query['trademark']);
        //     $query['trademark'] = $trademark;
        // }
        
        // if(key_exists('model', $query)){
        //     $model = explode(',',$query['model']);
        //     $query['model'] = $model;
        // }
        
        // if(key_exists('version', $query)){
        //     $version = explode(',',$query['version']);
        //     $query['version'] = $version;
        // }
        
        $limit = 24;
        if(key_exists('limit', $query) && (int) $query['limit']){
            $limit = $query['limit'];
        }

        $demands = $this->em->getRepository(Demand::class)->search($query, $limit);
        $pagination = new DemandsCollection($demands);
        return $this->json($pagination, 200, [], [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'groups' => ['hide'],
        ]);
    }

    /**
     * @Rest\Get("/faqs")
     * @Rest\View()
     */
    public function faqs(Request $req)
    {
        $query = $req->query->all();
        if(is_null($query)) $query= [];
        
        $limit = 24;
        if(key_exists('limit', $query) && (int) $query['limit']){
            $limit = $query['limit'];
        }

        $faqs = $this->em->getRepository(Faq::class)->search($query, $limit);
        $pagination = new FaqsCollection($faqs);
        return $this->json($pagination, 200, [], [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'groups' => ['list'],
        ]);
    }
}
