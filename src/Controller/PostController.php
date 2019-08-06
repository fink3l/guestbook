<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\PostType;
use App\Service\PostManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PostController extends AbstractController
{
    /**
     * @var EntityRepository
     */
    private $posts;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var PostManagerInterface
     */
    private $manager;

    /**
     * @param EntityRepository $posts
     * @param PostManagerInterface $manager
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(EntityRepository $posts, PostManagerInterface $manager, UrlGeneratorInterface $urlGenerator)
    {
        $this->posts = $posts;
        $this->urlGenerator = $urlGenerator;
        $this->manager = $manager;
    }

    /**
     * @throws \Exception
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->render('post/list.html.twig', [
            'posts' => $this->posts->findAll(),
        ]);
    }

    /**
     * @throws \Exception
     *
     * @return Response
     */
    public function add(): Response
    {
        return $this->render('post/add.html.twig', [
            'form' => $this->getCreateForm()->createView(),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $form = $this->getCreateForm();
        $form->handleRequest($request);
        $redirectRoute = 'post_add';

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->update($form->getData());
            $redirectRoute = 'post_list';
        }

        return $this->redirectToRoute($redirectRoute);
    }

    /**
     * @return FormInterface
     */
    private function getCreateForm(): FormInterface
    {
        return $this->createForm(
            PostType::class,
            null,
            ['action' => $this->urlGenerator->generate('post_create')]
        );
    }
}
