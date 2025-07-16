<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Application\Services\TodoService;
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
