<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\City;
use App\Form\CityType;
use App\Entity\Faq;
use App\Form\FaqType;
use App\Entity\Trademark;
use App\Form\TrademarkType;
use App\Entity\Model;
use App\Form\ModelType;
use App\Entity\Version;
use App\Form\VersionType;
use App\Entity\Car;
use App\Form\CarType;
use App\Entity\User;
use App\Form\ProType;
use App\Form\PartType;
use App\Form\UserType;

class BackController extends AbstractController
{
    private $em;
    private $paginator;

    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    public function index()
    {
        return $this->render('back/index.html.twig', [
        ]);
    }
    
    // PROS
    public function pros(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(User::class)
                                ->createQueryBuilder('u')
                                ->where('u.id = :id OR u.lastname LIKE :key OR u.firstname LIKE :key OR u.companyName LIKE :key OR u.email LIKE :key')
                                ->andWhere('u.type = 1')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('u.id', 'DESC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(User::class)
        						->createQueryBuilder('u')
                                ->where('u.type = 1')
        						->orderBy('u.id', 'DESC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/pros.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function prosAdd(Request $req)
    {
        $user = new User();
        $user->setType(1);
        $form = $this->createForm(ProType::class, $user);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('back/pros_draft.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function prosEdit(User $user, Request $req)
    {
        if($user->getType() != 1)
            $this->createNotFoundException();
        $form = $this->createForm(ProType::class, $user, [
            'edit' => true
        ]);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('back/pros_draft.html.twig', [
            'form' => $form->createView(),
            'edit' => true
        ]);
    }
    
    // PARTS
    public function parts(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(User::class)
                                ->createQueryBuilder('u')
                                ->where('u.id = :id OR u.lastname LIKE :key OR u.firstname LIKE :key OR u.companyName LIKE :key OR u.email LIKE :key')
                                ->andWhere('u.type = 0')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('u.id', 'DESC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(User::class)
        						->createQueryBuilder('u')
                                ->where('u.type = 0')
        						->orderBy('u.id', 'DESC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/parts.html.twig', [
            'pagination' => $pagination
        ]);
    }
     public function partsAdd(Request $req)
    {
        $user = new User();
        $user->setType(0);
        $form = $this->createForm(PartType::class, $user);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('back/parts_draft.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function partsEdit(User $user, Request $req)
    {
        if($user->getType() != 0)
            $this->createNotFoundException();
        $form = $this->createForm(PartType::class, $user, [
            'edit' => true
        ]);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('back/parts_draft.html.twig', [
            'form' => $form->createView(),
            'edit' => true
        ]);
    }
    
    
    // USERS
    public function users(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(User::class)
                                ->createQueryBuilder('u')
                                ->where('u.id = :id OR u.lastname LIKE :key OR u.firstname LIKE :key OR u.companyName LIKE :key OR u.email LIKE :key')
                                ->andWhere('u.roles LIKE "ROLE_ADMIN" OR u.roles LIKE "ROLE_SUPER_ADMIN"')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('u.id', 'DESC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(User::class)
        						->createQueryBuilder('u')
                                ->where('u.roles LIKE \'%ROLE_ADMIN%\' OR u.roles LIKE \'%ROLE_SUPER_ADMIN%\'')
        						->orderBy('u.id', 'DESC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/users.html.twig', [
            'pagination' => $pagination
        ]);
    }
    public function usersAdd(Request $req)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('back/users_draft.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function usersEdit(User $user, Request $req)
    {
        $form = $this->createForm(PartType::class, $user, [
            'edit' => true
        ]);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

        }
        return $this->render('back/users_draft.html.twig', [
            'form' => $form->createView(),
            'edit' => true
        ]);
    }
    
    
    // CITIES
    public function cities(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(City::class)
                                ->createQueryBuilder('c')
                                ->where('c.id = :id OR c.name LIKE :key OR c.description LIKE :key')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('c.name', 'ASC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(City::class)
        						->createQueryBuilder('c')
        						->orderBy('c.name', 'ASC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/cities.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    public function citiesAdd(Request $req)
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($city);
            $this->em->flush();
            $this->addFlash('success', 'La ville "' . $city->getName() . ' ' . $city->getCp() . '" a été enregistrée.');
            return $this->redirectToRoute('back_cities');
        }
        return $this->render('back/cities_draft.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function citiesEdit(City $city)
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($city);
            $this->em->flush();
            $this->addFlash('success', 'La ville "' . $city->getName() . ' ' . $city->getCp() . '" a été modifiée.');
            return $this->redirectToRoute('back_cities_edit', [
                'id' => $city->getId()
            ]);
        }
        return $this->render('back/cities_draft.html.twig', [
            'edit' => true,
            'form' => $form->createView()
        ]);
    }
    
    // CARS
    public function cars(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(Car::class)
                                ->createQueryBuilder('c')
                                ->join('c.trademark', 't')
                                ->join('c.model', 'm')
                                ->join('c.version', 'v')
                                ->where('c.id = :id OR t.num LIKE :key OR t.name LIKE :key OR m.name LIKE :key OR v.name LIKE :key OR c.description LIKE :key')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('c.id', 'DESC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(Car::class)
        						->createQueryBuilder('c')
                                ->join('c.trademark', 't')
        						->orderBy('c.id', 'DESC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/cars.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function carsAdd(Request $req)
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($car);
            $this->em->flush();
            $this->addFlash('success', 'Le véhicule #' . $car->getId() . ' a été enregistré.');
            return $this->redirectToRoute('back_cars');
        }
        return $this->render('back/cars_draft.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function carsEdit(Car $car)
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($car);
            $this->em->flush();
            $this->addFlash('success', 'Le véhicule #' . $car->getId() . ' a été modifié.');
            return $this->redirectToRoute('back_cars_edit', [
                'id' => $car->getId()
            ]);
        }
        return $this->render('back/cars_draft.html.twig', [
            'edit' => true,
            'form' => $form->createView()
        ]);
    }
    
    // TRADEMARKS
    public function trademarks(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(Trademark::class)
                                ->createQueryBuilder('t')
                                ->where('t.id = :id OR t.name LIKE :key OR t.description LIKE :key')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('t.name', 'ASC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(Trademark::class)
        						->createQueryBuilder('t')
        						->orderBy('t.name', 'ASC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/trademarks.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    public function trademarksAdd(Request $req)
    {
        $trademark = new Trademark();
        $form = $this->createForm(TrademarkType::class,$trademark);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($trademark);
            $this->em->flush();
            $this->addFlash('success', 'La marque "' . $trademark->getName() . '" a été enregistrée.');
            return $this->redirectToRoute('back_trademarks');
        }
        return $this->render('back/trademarks_draft.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function trademarksEdit(Trademark $trademark, Request $req)
    {
        $form = $this->createForm(TrademarkType::class,$trademark);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($trademark);
            $this->em->flush();
            $this->addFlash('success', 'La marque "' . $trademark->getName() . '" a été modifiée.');
            return $this->redirectToRoute('back_trademarks_edit', [
                'id' => $trademark->getId()
            ]);
        }
        return $this->render('back/trademarks_draft.html.twig', [
            'edit' => true,
            'form' => $form->createView(),
        ]);
    }
    
    // MODELS
    public function models(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(Model::class)
                                ->createQueryBuilder('m')
                                ->where('m.id = :id OR m.name LIKE :key')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('m.name', 'ASC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(Model::class)
        						->createQueryBuilder('m')
        						->orderBy('m.name', 'ASC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/models.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function modelsAdd(Request $req)
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class,$model);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($model);
            $this->em->flush();
            $this->addFlash('success', 'La modèle "' . $model->getName() . '" a été enregistrée.');
            return $this->redirectToRoute('back_models');
        }
        return $this->render('back/models_draft.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function modelsEdit(Model $model, Request $req)
    {
        $form = $this->createForm(ModelType::class,$model);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($model);
            $this->em->flush();
            $this->addFlash('success', 'La modèle "' . $model->getName() . '" a été modifiée.');
            return $this->redirectToRoute('back_models_edit', [
                'id' => $model->getId()
            ]);
        }
        return $this->render('back/models_draft.html.twig', [
            'edit' => true,
            'form' => $form->createView(),
        ]);
    }

    // VERS
    public function versions(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(Version::class)
                                ->createQueryBuilder('v')
                                ->where('v.id = :id OR v.name LIKE :key')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('v.name', 'ASC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(Version::class)
        						->createQueryBuilder('v')
        						->orderBy('v.name', 'ASC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/versions.html.twig', [
            'pagination' => $pagination
        ]);
    }

    public function versionsAdd(Request $req)
    {
        $version = new Version();
        $form = $this->createForm(VersionType::class,$version);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($version);
            $this->em->flush();
            $this->addFlash('success', 'La version "' . $version->getName() . '" a été enregistrée.');
            return $this->redirectToRoute('back_versions');
        }
        return $this->render('back/versions_draft.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    public function versionsEdit(Version $version, Request $req)
    {
        $form = $this->createForm(ModelType::class,$version);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($version);
            $this->em->flush();
            $this->addFlash('success', 'La version "' . $version->getName() . '" a été modifiée.');
            return $this->redirectToRoute('back_versions_edit', [
                'id' => $version->getId()
            ]);
        }
        return $this->render('back/versions_draft.html.twig', [
            'edit' => true,
            'form' => $form->createView(),
        ]);
    }
    
    // POSTS
    public function posts()
    {
        return $this->render('back/posts.html.twig', [
        ]);
    }
    
    // DEMANDS
    public function demands()
    {
        return $this->render('back/demands.html.twig', [
        ]);
    }
    
    // OFFERS
    public function offers()
    {
        return $this->render('back/offers.html.twig', [
        ]);
    }
    
    // CONVER
    public function conversations()
    {
        return $this->render('back/conversations.html.twig', [
        ]);
    }
    
    // MESS
    public function messages()
    {
        return $this->render('back/messages.html.twig', [
        ]);
    }
    
    // RESP
    public function responses()
    {
        return $this->render('back/responses.html.twig', [
        ]);
    }
    
    // PLANS
    public function plans()
    {
        return $this->render('back/plans.html.twig', [
        ]);
    }

    // SUBS
    public function subscriptions()
    {
        return $this->render('back/subscriptions.html.twig', [
        ]);
    }

    // HIST
    public function historics()
    {
        return $this->render('back/historics.html.twig', [
        ]);
    }

    // FAQ
    public function faqs(Request $req)
    {
        $q = $req->query->get('q');
        if(!is_null($q)){
            $query = $this->em  ->getRepository(Faq::class)
                                ->createQueryBuilder('f')
                                ->where('f.id = :id OR f.title LIKE :key')
                                ->setParameter('key', '%' . $q . '%')
                                ->setParameter('id', $q)
                                ->orderBy('f.createdAt', 'DESC')
                                ->getQuery();
        }else{
            $query = $this->em 	->getRepository(Faq::class)
        						->createQueryBuilder('f')
        						->orderBy('f.createdAt', 'DESC')
        						->getQuery();
        }
    	$pagination = $this->paginator->paginate(
            $query,
            $req->query->getInt('page', 1),
            24
        );
        return $this->render('back/faqs.html.twig', [
            'pagination' => $pagination
        ]);
    }
    
    public function faqsAdd(Request $req)
    {
        $faq = new Faq();
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($faq);
            $this->em->flush();
            $this->addFlash('success', 'L\'article "' . $faq->getTitle() . '" a été enregistré.');
            return $this->redirectToRoute('back_faqs');
        }
        return $this->render('back/faqs_draft.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function faqsEdit(Faq $faq, Request $req)
    {
        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($faq);
            $this->em->flush();
            $this->addFlash('success', 'L\'article "' . $faq->getTitle() . '" a été modifié.');
            return $this->redirectToRoute('back_faqs_edit', [
                'id' => $faq->getId()
            ]);
        }
        return $this->render('back/faqs_draft.html.twig', [
            'edit' => true,
            'form' => $form->createView()
        ]);
    }
}
