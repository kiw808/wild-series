<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class ProfileController
 * @package App\Controller
 * @Route("/my-profile", name="my_profile_")
 * @IsGranted("ROLE_SUBSCRIBER")
 */
class ProfileController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="show")
     */
    public function myProfile()
    {
        $user = $this->security->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
