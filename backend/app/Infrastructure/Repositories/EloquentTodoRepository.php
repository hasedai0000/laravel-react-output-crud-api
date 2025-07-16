<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Todo\Repositories\TodoRepositoryInterface;
use App\Models\Todo;

class EloquentTodoRepository implements TodoRepositoryInterface
{
  public function findAll(): array
  {
    return Todo::all()->toArray();
  }
}
