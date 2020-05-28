<?php


namespace App\Variables;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Category extends AbstractController
{
    public function category()
    {
        $category = $this->getDoctrine()
            ->getRepository(\App\Entity\Category::class)
            ->findAll();

        return $category;
    }
}