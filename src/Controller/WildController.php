<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
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

    /**
     * @Route("/program/{slug<^[a-z0-9-]+$>}",
     *     defaults={"slug" = null},
     *     name="program"
     * )
     * @param string|null $slug
     * @return Response
     */
    public function showByProgram(?string $slug) :Response
    {
        if (!$slug) {
            throw $this->createNotFoundException(
                'No slug has been sent to find a program in program\'s table .'
            );
        }

        $title = $slug = preg_replace(
            '/-/',
            ' ',
            ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($title)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with ' . $slug . ' title, found in program\'s table .'
            );
        }

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findAll();

        if (!$seasons) {
            throw $this->createNotFoundException(
                'No season found in ' . $slug . ' program\'s table'
            );
        }

        return $this->render('wild/program.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }

    /**
     * @Route("/season/{id}",
     *     defaults={"id"= null},
     *     name="season"
     * )
     * @param int $id
     * @return Response
     */
    public function showBySeason(int $id) :Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBy(['season' => $id]);

        return $this->render('wild/season.html.twig', [
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }

    /**
     * @Route("/episode/{id}",
     *     defaults={"id"= null},
     *     name="episode"
     * )
     * @param int $id
     * @return Response
     */
    public function showByEpisode(int $id) :Response
    {
        $episode = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findOneBy(['id' => $id]);

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
        ]);
    }
}