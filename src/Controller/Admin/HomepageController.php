<?php 

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class HomepageController extends AbstractController 
{   


//    public function __construct(private RequestStack $requestStack)
//    {
    
//    } 

    #[Route('/', name:'admin.homepage.index')]
    public function index():Response
    {
        return $this->render('admin/index.html.twig');
    }

}