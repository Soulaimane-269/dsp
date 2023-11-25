<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/admin')]

class ProductController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private RequestStack $requestStack, private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/product', name: 'admin.product.index')]
    public function index(): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products'=>$this->productRepository->findAll(),

        ]);
    }

    #[Route('/product/form', name :'admin.product.form')]
    #[Route('/product/upadte/{id}', name :'admin.product.update')]
    public function form(int $id = null):Response
    {
        // création d'un formulaire
        $entity = $id ? $this->productRepository->find($id) : new Product();
        $type = ProductType::class;
        $form = $this->createForm($type, $entity);

        // récuperer la saisie précédente dans la requete http
        $form->handleRequest($this->requestStack->getMainRequest());

        // si le form est valide et soumis
        if($form->isSubmitted() && $form->isValid()){
            // dd($entity);

            $filename = ByteString::fromRandom(32)->lower();

            // getting uploadedFile class from entity
            $file = $entity->getImage();

            // si une image a ete selectionnee
            if($file instanceof UploadedFile){
                $fileExtension = $file->guessClientExtension();
                $filename =  "$filename.$fileExtension";
                //transfert de l'image
                $file->move('img', $filename);
                // modifier la propriété image de l'entite
                $entity->setImage($filename);
            }
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);

            //message de confirmation
            $message = $id ? 'Product updated' : 'Product created';

            // message flush : message stocké en session, supprimé suite à son affichage
            $this->addFlash('notice' ,$message);

            // redirection
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/form.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/delete/{id}', name: 'admin.product.delete')]
    public function delete(int $id):RedirectResponse
    {
        
        // selectionner l'entite à supprimer
        $entity= $this->productRepository->find($id);
        $file = $entity->getImage();

        unlink("img/$file");

        //supprimer l'entité
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
         // getting uploadedFile class from entity
        //message de confirmation
        $this->addFlash('notice' ,'Product deleted');

        // redirection
        return $this->redirectToRoute('admin.product.index');
    }
}
