<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * タスク一覧を取得
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = [
            ['id' => 1, 'title' => 'タスク1', 'completed' => false],
            ['id' => 2, 'title' => 'タスク2', 'completed' => true],
            ['id' => 3, 'title' => 'タスク3', 'completed' => false],
        ];

        return response()->json($tasks);
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
