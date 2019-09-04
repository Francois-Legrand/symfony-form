<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;



class ContactController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    /**
     * @Route("/fake", name="fake")
     */
    public function fakeContact():Response
    {
        $entity_manager = $this -> getDoctrine() -> getManager();

        $c = new contact();
        $c -> setDate("03.09.2019");
        $c -> setName("françois");
        $c -> setEmail("francois.legrand62800@gmail.com");
        $c -> setTel("07.81.11.31.67");
        $c -> setMessage("Hello World");

        $entity_manager -> persist($c);//genere le sql
        $entity_manager -> flush();//execute les requetes

        return new Response("contact".$c -> getid()."créer avec succes");
    }
    /**
     * @Route("/viewcontact", name="viewcontact")
     */
    public function ViewContact()
    {
        $r = $this -> getDoctrine() -> getRepository(contact::class);
        $contacts = $r -> findAll();
        return $this -> render("contact/list.html.twig",[
            "contacts" => $contacts
        ]);
    }
    /**
     * @Route("/newcontact", name="newcontact")
     */
    public function newcontact(){
        $message = new Contact;

        $form = $this -> createForm(ContactType::Class, $message);
        
        $form -> add("envoyer", SubmitType::Class,[
            'label' => 'envoyer'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On récupère les données :   

            $message = $form->getData();

            // On r&cupère l'Eentity manager : 

              $entity_manager = $this->getDoctrine()->getManager();

              // On sauvegarde : 

            $entity_manager->persist($message);

            // On flush : 

              $entity_manager->flush();

              // On quitte en reciant le user : 

            return new Response ("Hey ! New record : ".$message->getId());

    }
        return $this -> render ("form/form.html.twig",[
            "formulaire" => $form -> createView()
        ]);
    }
}
