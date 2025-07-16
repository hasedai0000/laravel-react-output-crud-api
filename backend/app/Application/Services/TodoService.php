<?php

namespace App\Application\Services;

use App\Domain\Todo\Repositories\TodoRepositoryInterface;

class TodoService
{
  private $todoRepository;

  public function __construct(
    TodoRepositoryInterface $todoRepository
  ) {
    $this->todoRepository = $todoRepository;
  }

  /**
   * Todoの一覧を取得
   * 
   * @return array
   */
  public function getTodos(): array
  {
    $todos = $this->todoRepository->findAll();
    $todos = array_map(function ($todo) {
      return [
        'id' => $todo['id'],
        'user_id' => $todo['user_id'],
        'title' => $todo['title'],
        'content' => $todo['content'],
        'status' => $todo['status'],
      ];
    }, $todos);
    return $todos;
  }
}
