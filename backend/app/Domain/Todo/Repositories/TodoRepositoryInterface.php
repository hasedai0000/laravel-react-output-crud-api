<?php

namespace App\Domain\Todo\Repositories;

interface TodoRepositoryInterface
{
 public function findAll(): array;
}
