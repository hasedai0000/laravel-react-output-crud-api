<?php

namespace App\Domain\Todo\ValueObjects;

class TodoStatus
{
  protected const COMPLETED = 'completed';
  protected const INCOMPLETE = 'incomplete';
  protected const DELETED = 'deleted';

  protected $value;

  public function __construct(string $value)
  {
    $this->value = $value;
  }

  public static function completed(): self
  {
    return new self(self::COMPLETED);
  }

  public static function incomplete(): self
  {
    return new self(self::INCOMPLETE);
  }

  public static function deleted(): self
  {
    return new self(self::DELETED);
  }

  public function isCompleted(): bool
  {
    return $this->value === self::COMPLETED;
  }

  public function isIncomplete(): bool
  {
    return $this->value === self::INCOMPLETE;
  }

  public function isDeleted(): bool
  {
    return $this->value === self::DELETED;
  }

  public function value(): string
  {
    return $this->value;
  }

  public function equals(self $other): bool
  {
    return $this->value === $other->value;
  }
}
