<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\WashList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->findBy(['active' => 1]);

        return $this->render('main/index.html.twig', ['cours' => $cours]);
    }
    
    #[Route('/delete/{id}', name: 'course_delete')]
    public function delete(Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $cour->setActive(0);
        $entityManager->flush();

        return $this->redirectToRoute('app_main');
    }
    #[Route('/add/{id}', name: 'add_wash_list')]
    public function add(Cours $cour, EntityManagerInterface $entityManager): Response
    {
        $washlist = new WashList();
        $washlist->setUser($this->getUser());
        $washlist->setCours($cour);
        $entityManager->persist($cour);
        $entityManager->flush();

        return $this->redirectToRoute('cours_wishlist');
    }
}
