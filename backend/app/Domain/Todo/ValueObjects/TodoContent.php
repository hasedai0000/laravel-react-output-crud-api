<?php

namespace App\Domain\Todo\ValueObjects;

class TodoContent
{
  protected $value;

  public function __construct(string $value)
  {
    $this->validateContent($value);
    $this->value = $value;
  }

  private function validateContent(string $value): void
  {
    if (empty(trim($value))) {
      throw new \DomainException('内容は空にできません');
    }

    if (mb_strlen($value) > 255) {
      throw new \DomainException('Todoの内容は255文字以内で入力してください');
    }

    if (mb_strlen($value) < 1) {
      throw new \DomainException('Todoの内容は1文字以上で入力してください');
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
