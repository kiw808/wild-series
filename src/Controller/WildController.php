<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WildController extends AbstractController
{
    /**
     * @Route("/wild", name="wild_index")
     * @return Response
     */
    public function index() :Response
    {
        return $this->render('wild/index.html.twig', [
            'website' => 'Wild Series',
        ]);
    }

    /**
     * @Route("/wild/show/{slug}",
     *     requirements={"slug"="[a-z0-9-]+"},
     *     defaults={"slug"="none"},
     *     name="wild_show"
     * )
     * @param string $slug
     * @return Response
     */
    public function show(string $slug) :Response
    {
        if ($slug != 'none') {
            $slugTitle = str_replace('-', ' ', $slug);
            $slugTitle = ucwords($slugTitle);
        } else {
            $slugTitle = 'Aucune série sélectionnée, veuillez choisir une série';
        }

        return $this->render('wild/show.html.twig', [
            'slugTitle' => $slugTitle
        ]);
    }
}