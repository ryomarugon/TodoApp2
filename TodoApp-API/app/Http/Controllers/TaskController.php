<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('order', 'asc')->get();
        return response()->json($tasks);
        // $service = new TaskService;
        // $tasks = $service->getAllTasks();
        // return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $task = new Task();
            $task->name = $request->input('name');
            $task->tags = $request->input('tags');
            $task->status = $request->input('status');
            $task->order = $request->input('order');
            $task->save();
            // return response()->json(['message' => 'Task created successfully']);
            return response()->json($task);
        } catch (\Exception $e) {
            // エラーハンドリング
            return response()->json(['error' => 'An error occurred while creating the task.'], 500);
        }
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

    public function updateTasksOrder(Request $request)
    {
        try {
            // リクエストからタスク情報を取得
            $tasksData = $request->json()->all();

            // タスク情報を更新
            foreach ($tasksData as $taskData) {
                $task = Task::find($taskData['id']); // タスクのIDが必要
                $task->status = $taskData['status'];
                $task->order = $taskData['order'];
                $task->save();
            }

            return response()->json(['message' => 'Task order updated successfully']);
        } catch (\Exception $e) {
            // エラーハンドリング
            return response()->json(['error' => 'An error occurred while updating tasks order.'], 500);
        }
    }
}
