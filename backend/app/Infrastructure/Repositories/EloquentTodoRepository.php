<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Todo\Repositories\TodoRepositoryInterface;
use App\Domain\Todo\Entities\Todo as TodoEntity;
use App\Domain\Todo\ValueObjects\TodoTitle;
use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoStatus;
use App\Models\Todo;

class EloquentTodoRepository implements TodoRepositoryInterface
{
  /**
   * Todoを全て取得
   * 
   * @return array
   */
  public function findAll(): array
  {
    return Todo::all()->toArray();
  }

  /**
   * 永続化
   * 
   * @param TodoEntity $todo
   * @return void
   */
  public function save(TodoEntity $todo): void
  {
    $eloquentTodo = new Todo();
    $eloquentTodo->id = $todo->getId();
    $eloquentTodo->user_id = $todo->getUserId();
    $eloquentTodo->title = $todo->getTitle()->value();
    $eloquentTodo->content = $todo->getContent()->value();
    $eloquentTodo->status = $todo->getStatus()->value();

    $eloquentTodo->save();
  }

  /**
   * Todoを取得
   * 
   * @param string $todo_id
   * @return TodoEntity
   */
  public function findById(string $todo_id): ?TodoEntity
  {
    $eloquentTodo = Todo::findOrFail($todo_id);

    return new TodoEntity(
      $eloquentTodo->id,
      $eloquentTodo->user_id,
      new TodoTitle($eloquentTodo->title),
      new TodoContent($eloquentTodo->content),
      new TodoStatus($eloquentTodo->status)
    );
  }
}
