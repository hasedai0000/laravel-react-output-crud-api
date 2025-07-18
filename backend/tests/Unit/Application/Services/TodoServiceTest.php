<?php

namespace Tests\Unit\Application\Services;

use App\Application\Services\TodoService;
use App\Domain\Todo\Entities\Todo as TodoEntity;
use App\Domain\Todo\Repositories\TodoRepositoryInterface;
use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoStatus;
use App\Domain\Todo\ValueObjects\TodoTitle;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TodoServiceTest extends TestCase
{
  use RefreshDatabase;

  private TodoService $todoService;

  private const TEST_USER = [
    'id' => 1,
    'name' => 'test_user',
    'email' => 'test@example.com',
    'password' => 'password',
  ];

  private const TEST_TODO = [
    'id' => "12345678910",
    'user_id' => self::TEST_USER['id'],
    'title' => 'test_title',
    'content' => 'test_content',
    'status' => 'incomplete',
  ];

  public function setUp(): void
  {
    parent::setUp();

    $this->mock(
      TodoRepositoryInterface::class,
      function (MockInterface $mock) {
        $mock->shouldReceive('findAll')->andReturn([
          [
            'id' => self::TEST_TODO['id'],
            'user_id' => self::TEST_USER['id'],
            'title' => self::TEST_TODO['title'],
            'content' => self::TEST_TODO['content'],
            'status' => self::TEST_TODO['status'],
          ]
        ]);

        $mock->shouldReceive('findById')->andReturn(new TodoEntity(
          self::TEST_TODO['id'],
          self::TEST_TODO['user_id'],
          new TodoTitle(self::TEST_TODO['title']),
          new TodoContent(self::TEST_TODO['content']),
          new TodoStatus(self::TEST_TODO['status']),
        ));

        $mock->shouldReceive('save')->andReturn(new TodoEntity(
          self::TEST_TODO['id'],
          self::TEST_TODO['user_id'],
          new TodoTitle(self::TEST_TODO['title']),
          new TodoContent(self::TEST_TODO['content']),
          new TodoStatus(self::TEST_TODO['status']),
        ));

        $mock->shouldReceive('delete')->andReturn(null);
      }
    );

    $this->todoService = app(TodoService::class);
  }

  #[Test]
  public function testGetTodos(): void
  {
    $user = User::factory()->create([
      'id' => self::TEST_USER['id'],
      'name' => self::TEST_USER['name'],
      'email' => self::TEST_USER['email'],
      'password' => self::TEST_USER['password'],
    ]);

    $todo = Todo::factory()->create([
      'id' => self::TEST_TODO['id'],
      'user_id' => $user->id,
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ]);

    $expected = [
      [
        'id' => self::TEST_TODO['id'],
        'user_id' => $user->id,
        'title' => $todo->title,
        'content' => $todo->content,
        'status' => $todo->status,
      ]
    ];

    $todos = $this->todoService->getTodos();
    $this->assertEquals($expected, $todos);
  }

  #[Test]
  public function testCreateTodo(): void
  {
    $user = User::factory()->create([
      'id' => self::TEST_USER['id'],
      'name' => self::TEST_USER['name'],
      'email' => self::TEST_USER['email'],
      'password' => self::TEST_USER['password'],
    ]);

    $todo = $this->todoService->createTodo($user->id, self::TEST_TODO['title'], self::TEST_TODO['content']);

    $expected = [
      'id' => $todo->getId(),
      'user_id' => $user->id,
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ];

    $this->assertEquals($expected, $todo->toArray());
  }

  #[Test]
  public function testGetTodo(): void
  {
    $user = User::factory()->create([
      'id' => self::TEST_USER['id'],
      'name' => self::TEST_USER['name'],
      'email' => self::TEST_USER['email'],
      'password' => self::TEST_USER['password'],
    ]);

    $todo = Todo::factory()->create([
      'id' => self::TEST_TODO['id'],
      'user_id' => $user->id,
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ]);

    $todo = $this->todoService->getTodo(self::TEST_TODO['id']);

    $expected = [
      'id' => self::TEST_TODO['id'],
      'user_id' => self::TEST_USER['id'],
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ];

    $this->assertEquals($expected, $todo->toArray());
  }

  #[Test]
  public function testUpdateTodo(): void
  {
    $user = User::factory()->create([
      'id' => self::TEST_USER['id'],
      'name' => self::TEST_USER['name'],
      'email' => self::TEST_USER['email'],
      'password' => self::TEST_USER['password'],
    ]);

    $todo = Todo::factory()->create([
      'id' => self::TEST_TODO['id'],
      'user_id' => $user->id,
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ]);

    $newTitle = 'new_title';
    $newContent = 'new_content';
    $newStatus = 'complete';

    $todo = $this->todoService->updateTodo(self::TEST_TODO['id'], $newTitle, $newContent, $newStatus);

    $expected = [
      'id' => self::TEST_TODO['id'],
      'user_id' => self::TEST_USER['id'],
      'title' => $newTitle,
      'content' => $newContent,
      'status' => $newStatus,
    ];

    $this->assertEquals($expected, $todo->toArray());
  }

  #[Test]
  public function testDeleteTodo(): void
  {
    $user = User::factory()->create([
      'id' => self::TEST_USER['id'],
      'name' => self::TEST_USER['name'],
      'email' => self::TEST_USER['email'],
      'password' => self::TEST_USER['password'],
    ]);

    $todo = Todo::factory()->create([
      'id' => self::TEST_TODO['id'],
      'user_id' => $user->id,
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ]);

    $this->todoService->deleteTodo($todo->id);

    $this->assertNull(null);
  }
}
