<?php

namespace App\Models;

use App\Domain\Todo\ValueObjects\TodoContent;
use App\Domain\Todo\ValueObjects\TodoStatus;
use App\Domain\Todo\ValueObjects\TodoTitle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
  /** @use HasFactory<\Database\Factories\TodoFactory> */
  use HasFactory, SoftDeletes;

  protected $table = 'todos';

  protected $primaryKey = 'id';

  protected $dates = ['deleted_at'];

  protected $fillable = [
    'user_id',
    'title',
    'content',
    'status',
  ];

  protected function casts(): array
  {
    return [
      'user_id' => 'integer',
      'title' => TodoTitle::class,
      'content' => TodoContent::class,
      'status' => TodoStatus::class,
    ];
  }
}
