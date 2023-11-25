<?php 

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController 
{   


    public function __construct(private ProductRepository $productRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
        
    }
    #[Route('/contact', name:'contact.index')]
    public function index(int $id = null):Response
    {
         // création d'un formulaire
         $entity = $id ? $this->productRepository->find($id) : new Contact();
         $type = ContactType::class;
         $form = $this->createForm($type, $entity);

        

        // si le form est valide et soumis
        if($form->isSubmitted() && $form->isValid()){
            // dd($entity);
           
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);

            //message de confirmation
            $message = 'Message sent successfully';

            // message flush : message stocké en session, supprimé suite à son affichage
            $this->addFlash('notice' ,$message);

            // redirection
            return $this->redirectToRoute('contact.index');
        }
         return $this->render('contact/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

}