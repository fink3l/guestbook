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
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    /**
     * @var EntityRepository
     */
    private $posts;

    /**
     * @var PostManagerInterface
     */
    private $manager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param EntityRepository $posts
     * @param PostManagerInterface $manager
     * @param SerializerInterface $serializer
     */
    public function __construct(
        EntityRepository $posts,
        PostManagerInterface $manager,
        SerializerInterface $serializer
    ) {
        $this->posts = $posts;
        $this->manager = $manager;
        $this->serializer = $serializer;
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
     * @return Response
     */
    public function export(): Response
    {
        $data = $this->serializer->serialize($this->posts->findAll(), 'csv');

        return new Response($data, 200, ['Content-Type' => 'text/csv']);
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
            ['action' => $this->generateUrl('post_create')]
        );
    }
}
