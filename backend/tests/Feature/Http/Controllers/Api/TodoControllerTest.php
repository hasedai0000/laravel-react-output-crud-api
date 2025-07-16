<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoTitle;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TodoControllerTest extends TestCase
{
  use RefreshDatabase;

  private const TEST_USER = [
    'id' => 1,
    'name' => 'test_user',
    'email' => 'test@example.com',
    'password' => 'password',
  ];

  private const TEST_TODO = [
    'user_id' => 1,
    'title' => 'testTitle',
    'content' => 'testContent',
    'status' => 'incomplete',
  ];

  private User $user;
  private Todo $todo;

  public function setUp(): void
  {
    parent::setUp();
    $this->user = User::factory()->create([
      'id' => self::TEST_USER['id'],
      'name' => self::TEST_USER['name'],
      'email' => self::TEST_USER['email'],
      'password' => self::TEST_USER['password'],
    ]);
    $this->todo = Todo::factory()->create([
      'user_id' => self::TEST_TODO['user_id'],
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
      'status' => self::TEST_TODO['status'],
    ]);
  }

  private function assertSuccessResponse(array $response, array $data, string $message): void
  {
    $this->assertTrue($response['success']);
    $this->assertEquals($data, $response['data']);
    $this->assertEquals($message, $response['message']);
  }

  private function assertErrorResponse(array $response, string $message): void
  {
    $this->assertFalse($response['success']);
    $this->assertEquals($message, $response['message']);
  }

  #[Test]
  public function testIndexSuccess(): void
  {
    $response = $this->getJson('api/todos')->assertStatus(200)->assertJsonStructure([
      'success',
      'data' => ['todos'],
      'message',
    ]);

    $this->assertSuccessResponse(
      $response->json(),
      ['todos' => [
        [
          'id' => $this->todo->id,
          'user_id' => $this->todo->user_id,
          'title' => $this->todo->title,
          'content' => $this->todo->content,
          'status' => $this->todo->status,
        ]
      ]],
      'Todoの一覧を取得しました'
    );
  }

  #[Test]
  public function testStoreSuccess(): void
  {
    $response = $this->postJson('api/todos', [
      'user_id' => $this->user->id,
      'title' => self::TEST_TODO['title'],
      'content' => self::TEST_TODO['content'],
    ])->assertStatus(200)
      ->assertJsonStructure([
        'success',
        'data' => ['todo'],
        'message',
      ]);

    $this->assertSuccessResponse(
      $response->json(),
      ['todo' => [
        'id' => $response->json('data.todo.id'),
        'user_id' => $this->user->id,
        'title' => self::TEST_TODO['title'],
        'content' => self::TEST_TODO['content'],
        'status' => self::TEST_TODO['status'],
      ]],
      'Todoを作成しました'
    );
  }

  #[Test]
  public function testShowSuccess(): void
  {
    $response = $this->getJson('api/todos/' . $this->todo->id)
      ->assertStatus(200)
      ->assertJsonStructure([
        'success',
        'data' => ['todo'],
        'message',
      ]);

    $this->assertSuccessResponse(
      $response->json(),
      ['todo' => [
        'id' => $this->todo->id,
        'user_id' => $this->todo->user_id,
        'title' => $this->todo->title,
        'content' => $this->todo->content,
        'status' => $this->todo->status,
      ]],
      'Todoを取得しました'
    );
  }
}
