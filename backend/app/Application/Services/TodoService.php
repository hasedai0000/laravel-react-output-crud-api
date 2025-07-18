<?php

namespace App\Application\Services;

use App\Domain\Todo\Repositories\TodoRepositoryInterface;
use App\Domain\Todo\Entities\Todo as TodoEntity;
use App\Domain\Todo\ValueObjects\TodoTitle;
use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoStatus;
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

  /**
   * Todoを取得
   * 
   * @param string $id
   * @return TodoEntity
   */
  public function getTodo(string $todo_id): ?TodoEntity
  {
    return $this->todoRepository->findById($todo_id);
  }

  /**
   * Todoを更新
   * 
   * @param string $id
   * @param string $title
   * @param string $content
   * @param string $status
   * @return TodoEntity|null
   */
  public function updateTodo(string $id, string $title, string $content, string $status): ?TodoEntity
  {
    $todoTitle = new TodoTitle($title);
    $todoContent = new TodoContent($content);
    $todoStatus = new TodoStatus($status);

    $todo = $this->todoRepository->findById($id);

    if (!$todo) {
      return null;
    }

    $todo->setTitle($todoTitle);
    $todo->setContent($todoContent);
    $todo->setStatus($todoStatus);

    $this->todoRepository->save($todo);
    return $todo;
  }

  /**
   * Todoを削除
   * 
   * @param string $id
   * @return void
   */
  public function deleteTodo(string $id): void
  {
    $this->todoRepository->delete($id);
  }
}
