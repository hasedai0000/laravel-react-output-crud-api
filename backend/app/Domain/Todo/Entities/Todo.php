<?php

namespace App\Domain\Todo\Entities;

use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoStatus;
use App\Domain\Todo\ValueObjects\TodoTitle;

class Todo
{
  protected $id;
  protected $user_id;
  protected $title;
  protected $content;
  protected $status;

  public function __construct(
    ?int $id,
    int $user_id,
    string $title,
    string $content,
    ?bool $status
  ) {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->title = $title;
    $this->content = $content;
    $this->status = TodoStatus::incomplete();
  }

  public function getId(): int
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

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  public function setStatus(bool $status): void
  {
    $this->status = $status;
  }

  public function jsonSerialize(): array
  {
    return [
      'id' => $this->getId(),
      'user_id' => $this->getUserId(),
      'title' => $this->getTitle(),
      'content' => $this->getContent(),
      'status' => $this->getStatus(),
    ];
  }
}
