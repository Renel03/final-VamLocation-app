<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Demand;
use App\Form\DemandTestType;

class HomeController extends AbstractController
{
    public function page($slug)
    {
        return $this->render('home/page.html.twig', [
            'slug' => $slug
        ]);
    }
    
    public function test_demand(Request $req)
    {
        $demand = new Demand();
        $form = $this->createForm(DemandTestType::class, $demand);
        $form->handleRequest($req);
        // if($form->isSubmitted()){
        //     dump($req->request);die;
        // }  
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($demand);
            $em->flush();
            $this->addFlash('success', "Demande envoyé");
            return $this->redirectToRoute('test_demand');
        }
        return $this->render('home/test_demand.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
