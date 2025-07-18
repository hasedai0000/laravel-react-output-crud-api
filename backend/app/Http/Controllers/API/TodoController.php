<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Application\Services\TodoService;
use App\Http\Requests\Todo\TodoStoreRequest;
use App\Http\Requests\Todo\TodoUpdateRequest;
use Illuminate\Http\Request;

class TodoController extends BaseController
{
    private $todoService;

    public function __construct(
        TodoService $todoService
    ) {
        $this->todoService = $todoService;
    }

    /**
     * Todoの一覧を取得
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = $this->todoService->getTodos();
        $success['todos'] = $todos;

        return $this->sendResponse($success, 'Todoの一覧を取得しました');
    }

    /**
     * Todoを作成
     * 
     * @param TodoStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TodoStoreRequest $request)
    {
        try {
            // バリデーション済みデータの取得
            $validatedData = $request->validated();

            // アプリケーションサービスにロジックを委譲
            $todo = $this->todoService->createTodo(
                $validatedData['user_id'],
                $validatedData['title'],
                $validatedData['content']
            );

            $success['todo'] = $todo->toArray();

            return $this->sendResponse($success, 'Todoを作成しました');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Todoを取得
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $todo = $this->todoService->getTodo($id);

        if (!$todo) {
            return $this->sendError('Todoが見つかりません');
        }

        $success['todo'] = $todo->toArray();

        return $this->sendResponse($success, 'Todoを取得しました');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoUpdateRequest $request, string $id)
    {
        try {
            // バリデーション済みデータの取得
            $validatedData = $request->validated();

            // アプリケーションサービスにロジックを委譲
            $todo = $this->todoService->updateTodo(
                $id,
                $validatedData['title'],
                $validatedData['content'],
                $validatedData['status'],
            );

            if (!$todo) {
                return $this->sendError('Todoが見つかりません');
            }

            $success['todo'] = $todo->toArray();

            return $this->sendResponse($success, 'Todoを更新しました');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Todoを削除
     * 
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
        $todo = $this->todoService->getTodo($id);

        if (!$todo) {
            return $this->sendError('Todoが見つかりません');
        }

        try {
            $this->todoService->deleteTodo($id);

            return $this->sendResponse([], 'Todoを削除しました');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
