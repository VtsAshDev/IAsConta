<?php

namespace App\Service\CategoryService;

use App\Repository\CategoryRepository;

class CategoryService
{
    public function __construct(
     private CategoryRepository $repository
    ) {
    }

    public function findAllAsText(): string
    {
        $categories = array_map(fn($c) => $c->getName(), $this->repository->findAll());

        return implode(', ', $categories);
    }
}
