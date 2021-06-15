<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Actor;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

/**
 * @Route("/actors", name="actor_")
 */
Class ActorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $actors = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();

        return $this->render('actor/index.html.twig', [
            'actors' => $actors
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     * @return Response A response instance
     */
    public function show(Actor $actor, Program $program): Response
    {
        $actor = $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findOneBy(['id' => $actor]); 

        if (!$actor) {
            throw $this->createNotFoundException(
                'No Actor with id : '.$actor.' found in actor\'s table.'
            );
        }

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'program' => $program
        ]);
    }

}