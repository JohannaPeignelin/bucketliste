<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {
        //TODO renvoyer la liste des choses à faire
        $wishs = $wishRepository->findBy([],["dateCreated" => "ASC"], 30);

        dump($wishs);
        return $this->render('wish/list.html.twig',[
            'wishs' => $wishs
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);

        if (!$wish){
            throw $this->createNotFoundException("Ce wish n'existe pas ! ");
        }
        dump($id);
        //TODO renvoyer le détail d'un item de la Bucket Liste
        return $this->render('wish/show.html.twig',[
            'wish' => $wish
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, WishRepository $wishRepository): Response
    {

        $wish = new Wish();

        //instanciation du formulaire en lui passant l'interface de Wish
        $wishForm = $this->createForm(WishType::class,$wish);

        //permet d'extraire les données de la requête
        $wishForm->handleRequest($request);

        //on rentre dans le if si appui sur submit et TODO  si les asserts sont valides
        if ($wishForm->isSubmitted() && $wishForm->isValid()){
            //traitement de la donnée
            //enregistre le wish dans la BDD

            $wishRepository->save($wish, true);

            //redirige vers la page de détails avec message de confirmation
            $this->addFlash('success', 'Idea successfully added ! ');
            return $this->redirectToRoute('wish_show', ['id' => $wish->getId()]);
        }

        return $this->render('wish/add.html.twig', [
            'wishForm' => $wishForm->createView()
        ]);
    }

}
