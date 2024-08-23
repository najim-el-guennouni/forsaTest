<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Entity\WashList;
use App\Repository\WashListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class CoursController extends AbstractController
{
    
    #[Route('/cours', name: 'cours')]
    public function cours(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        return $this->render('cours/home.html.twig');
    }
    #[Route('/cours/new', name: 'cours_new')]
    public function ajoute(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $cours = new Cours();
        $form = $this->createForm(CoursType::class, $cours);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cours->setUser($this->getUser());
            $cours->setActive(1);
            
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception during file upload
                }

                $cours->setImage($newFilename);
            }

            $cours->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($cours);
            $entityManager->flush();

            // Redirect to the course list or a success page
            return $this->redirectToRoute('app_main');
        }

        return $this->render('cours/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/cours/wishlist', name: 'cours_wishlist')]
    public function wishlist(WashListRepository $washListRepository): Response
    {
        $user = $this->getUser(); 
        if (!$user) {
            throw $this->createAccessDeniedException('You need to be logged in to view your wishlist.');
        }

        $wishlist = $washListRepository->findWishlistByUser($user);

        return $this->render('wishlist/index.html.twig', [
            'wishlist' => $wishlist,
        ]);
    }
    
    #[Route('/delete/{id}', name: 'course_delete_wish_list')]
    public function delete(WashList $washlist, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($washlist);
        $entityManager->flush();

        return $this->redirectToRoute('cours_wishlist');
    }
}
