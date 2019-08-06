<?php

declare(strict_types=1);

namespace App\Exception;

use App\Entity\Post;

class PostUpdateException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(Post $post, $code = 0, \Exception $previous = null)
    {
        $message = \sprintf('Failed update Post with id "%s" and title "%s"', $post->getId(), $post->getTitle());
        parent::__construct($message, $code, $previous);
    }
}
