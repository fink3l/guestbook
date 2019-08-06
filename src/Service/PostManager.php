<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;
use App\Exception\PostUpdateException;
use Doctrine\ORM\EntityManagerInterface;

class PostManager implements PostManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function update(Post $post): Post
    {
        try {
            $this->entityManager->beginTransaction();
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            throw new PostUpdateException($post, 0, $e);
        }

        return $post;
    }
}
