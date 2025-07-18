<?php

namespace App\Domain\Todo\Repositories;

use App\Domain\Todo\Entities\Todo as TodoEntity;

interface TodoRepositoryInterface
{
 public function findAll(): array;
 public function save(TodoEntity $todo): void;
 public function findById(string $todo_id): ?TodoEntity;
 public function delete(string $todo_id): void;
}
