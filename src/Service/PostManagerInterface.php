<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Post;

interface PostManagerInterface
{
    /**
     * @param Post $post
     *
     * @return Post
     */
    public function update(Post $post): Post;
}
