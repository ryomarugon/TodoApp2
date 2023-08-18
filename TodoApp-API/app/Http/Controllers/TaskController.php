<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        return response()->json($tasks);
    }

    public function store(Request $request)
    {
        // バリデーションやリクエストの加工を行う部分
        // ...

        $task = $this->taskService->createTask($request->all());
        return response()->json($task);
    }

    public function updateTasksOrder(Request $request)
    {
        // バリデーションやリクエストの加工を行う部分
        // ...
        $tasksData = $request->json()->all();
        $this->taskService->updateTaskOrder($tasksData);
        return response()->json(['message' => 'Task order updated successfully']);
    }
}
