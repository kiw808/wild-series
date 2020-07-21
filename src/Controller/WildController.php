<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\CommentType;
use App\Form\ProgramSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class WildController
 * @package App\Controller
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) :Response
    {
        // Search form
        $form = $this->createForm(ProgramSearchType::class, null, ['method' => Request::METHOD_GET]);
        $form->handleRequest($request);

        $message = "";

        if ($form->isSubmitted()) {
            $data = $form->getData();

            $program = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findOneBy(['title' => $data]);

            if (!$program) {
                $message  = $data['searchField'];
            } else {
                return $this->render('wild/show.html.twig', [
                    'program' => $program,
                ]);
            }
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render('wild/index.html.twig', [
            'message' => $message,
            'programs' => $programs,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{slug}", name="show")
     * @param Program $program
     * @return Response
     */
    public function show(Program $program) :Response
    {
        return $this->render('wild/show.html.twig', [
            'program' => $program,
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

        $seasons = $program->getSeasons();

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
     * @Route("/season/{id}", defaults={"id"= null}, name="season")
     * @param int $id
     * @return Response
     */
    public function showBySeason(int $id) :Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        // $program = $season->getProgram();
        // $episodes = $season->getEpisodes();

        /* $slug = strtolower(
            str_replace(
                ' ',
                '-',
                $program->getTitle()
            )
        ); */

        return $this->render('wild/season.html.twig', [
            // 'program' => $program,
            'season' => $season,
            // 'episodes' => $episodes,
            // 'slug' => $slug,
        ]);
    }

    /**
     * @Route("/{slug}/{id}",
     *     defaults={"id"= null},
     *     name="episode"
     * )
     * @param Episode $episode
     * @param Request $request
     * @return Response
     */
    public function showByEpisode(Episode $episode, Request $request) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();
        $slug = strtolower(
            str_replace(
                ' ',
                '-',
                $program->getTitle()
            )
        );

        // COMMENT FORM //
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        // Form processing
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            // Fetch logged user
            $user = $this->security->getUser();

            // Populate entity
            $comment = $commentForm->getData();
            $comment->setAuthor($user);
            $comment->setEpisode($episode);

            // Insert in database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirection
            return $this->redirectToRoute('wild_episode', [
                'slug' => $program->getSlug(),
                'id' => $episode->getId(),
            ]);
        }

        return $this->render('wild/episode.html.twig', [
            'episode' => $episode,
            'commentForm' => $commentForm->createView(),
            'season' => $season,
            'program' => $program,
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/delete_comment", name="delete_comment")
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function deleteComment(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        // Redirection
        return $this->redirectToRoute('wild_episode', [
            'slug' => $comment->getEpisode()->getSeason()->getProgram()->getSlug(),
            'id' => $comment->getEpisode()->getId(),
        ]);
    }

    /**
     * @Route("/addCategory", name="addCategory")
     * @param Request $request
     * @return Response
     */
    public function addCategory(Request $request) :Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
        }

        return $this->render('wild/addCategory.html.twig', [
            'form' => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/actor/{id}", name="actor")
     * @param Actor $actor
     * @return Response
     */
    public function showByActor(Actor $actor) :Response
    {
        return $this->render('wild/actor.html.twig', [
            'actor' => $actor,
        ]);
    }
}
