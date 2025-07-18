<?php

namespace App\Http\Requests\Todo;

use Illuminate\Foundation\Http\FormRequest;

class TodoUpdateRequest extends FormRequest
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
      'title' => ['required', 'string', 'max:100'],
      'content' => ['required', 'string', 'max:255'],
      'status' => ['required', 'string', 'in:incomplete,complete,deleted'],
    ];
  }
}
