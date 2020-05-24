<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('wild/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * @Route("/show/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="show"
     * )
     * @param string $slug
     * @return Response
     */
    public function show(?string $slug) :Response
    {
        if (!$slug) {
            throw $this->createNotFoundException(
                'No slug has been sent to find a program in program\'s table .'
            );
        }

        $slug = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table .'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName}",
     *     defaults={"categoryName" = null},
     *     name="category")
     * @param string|null $categoryName
     * @return Response
     */
    public function showByCategory(?string $categoryName) :Response
    {
        if (!$categoryName) {
            throw $this->createNotFoundException(
                'No category name has been sent to find a category in category\'s table'
            );
        }

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'desc'],
                3
            );

        if (!$programs) {
            throw $this->createNotFoundException(
                'No programs with ' . $categoryName . ' category name found in program\'s table'
            );
        }

        return $this->render('wild/category.html.twig', [
            'category' => $category,
            'programs' => $programs,
        ]);
    }
}