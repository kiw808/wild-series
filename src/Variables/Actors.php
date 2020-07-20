<?php


namespace App\Variables;


use App\Entity\Actor;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Actors extends AbstractController
{
    /**
     * @return Actor[]|object[]
     */
    public function actors()
    {
        return $this->getDoctrine()
            ->getRepository(Actor::class)
            ->findAll();
    }
}