<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class TodoStoreRequest extends FormRequest
{
  /**
   * 認可
   * 
   * @return bool
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * バリデーションルール
   * 
   * @return array
   */
  public function rules(): array
  {
    return [
      'user_id' => ['required', 'integer', 'exists:users,id'],
      'title' => ['required', 'string', 'max:100'],
      'content' => ['required', 'string', 'max:255'],
    ];
  }
}
