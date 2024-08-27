<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\WashList;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager,PaginatorInterface $paginator, Request $request): Response
    {
     $cours = $paginator->paginate(
        $entityManager->getRepository(Cours::class)->findBy(['active' => 1]), 
        $request->query->getInt('page', 1), /*page number*/
        6 /*limit per page*/
    );

        return $this->render('main/index.html.twig', ['cours' => $cours]);
    }
    
    #[Route('/delete/{id}', name: 'course_delete')]
    public function delete(Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $cour->setActive(0);
        $entityManager->flush();
        $this->addFlash('success', 'The course has been successfully deleted.');

        return $this->redirectToRoute('app_main');
    }
    #[Route('/add_wash_list/{id}', name: 'add_wash_list')]
    public function addWashList(Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $washlist = new WashList();
        $washlist->setUser($this->getUser());
        $washlist->setCours($cour);
        $entityManager->persist($washlist);
        $entityManager->flush();
        
        $this->addFlash('success', 'The course has been added to your wishlist.');

        return $this->redirectToRoute('app_main');
    }
}
