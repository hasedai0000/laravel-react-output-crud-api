<?php

namespace App\Application\Services;

use App\Domain\Todo\Repositories\TodoRepositoryInterface;
use App\Domain\Todo\Entities\Todo as TodoEntity;
use App\Domain\Todo\ValueObjects\TodoTitle;
use App\Domain\Todo\ValueObjects\TodoContent;
use Illuminate\Support\Str;

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

  /**
   * Todoを作成
   * 
   * @param int $user_id
   * @param string $title
   * @param string $content
   * @return TodoEntity
   */
  public function createTodo(int $user_id, string $title, string $content): TodoEntity
  {
    $todoTitle = new TodoTitle($title);
    $todoContent = new TodoContent($content);

    $todo = new TodoEntity(
      Str::uuid()->toString(),
      $user_id,
      $todoTitle,
      $todoContent
    );

    // リポジトリを使用して永続化
    $this->todoRepository->save($todo);

    return $todo;
  }
}
