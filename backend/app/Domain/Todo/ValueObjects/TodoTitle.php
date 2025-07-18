<?php

namespace App\Domain\Todo\ValueObjects;

class TodoTitle
{
  protected $value;

  public function __construct(string $value)
  {
    $this->validateTitle($value);
    $this->value = $value;
  }

  private function validateTitle(string $value): void
  {
    if (empty(trim($value))) {
      throw new \DomainException('タイトルは空にできません');
    }

    if (mb_strlen($value) > 100) {
      throw new \DomainException('タイトルは100文字以内で入力してください');
    }

    if (mb_strlen($value) < 1) {
      throw new \DomainException('タイトルは1文字以上で入力してください');
    }
  }

  public function value(): string
  {
    return $this->value;
  }

  public function isEmpty(): bool
  {
    return empty(trim($this->value));
  }

  /**
   * 値オブジェクトの比較（同値性）
   */
  public function equals(self $other): bool
  {
    return $this->value === $other->value;
  }

  /**
   * 文字列としても利用可能
   */
  public function __toString(): string
  {
    return $this->value;
  }
}
