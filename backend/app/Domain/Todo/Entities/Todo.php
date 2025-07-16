<?php

namespace App\Domain\Todo\Entities;

use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoStatus;
use App\Domain\Todo\ValueObjects\TodoTitle;

class Todo
{
  private $id;
  private $user_id;
  private $title;
  private $content;
  private $status;

  public function __construct(
    string $id,
    int $user_id,
    TodoTitle $title,
    TodoContent $content
  ) {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->title = $title;
    $this->content = $content;
    $this->status = TodoStatus::incomplete();
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function getUserId(): int
  {
    return $this->user_id;
  }

  public function getTitle(): TodoTitle
  {
    return $this->title;
  }

  public function getContent(): TodoContent
  {
    return $this->content;
  }

  public function getStatus(): TodoStatus
  {
    return $this->status;
  }

  /**
   * エンティティを配列形式に変換
   * 
   * @return array
   */
  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'user_id' => $this->user_id,
      'title' => $this->title->value(),
      'content' => $this->content->value(),
      'status' => $this->status->value(),
    ];
  }
}
