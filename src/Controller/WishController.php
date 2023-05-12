<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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

}
