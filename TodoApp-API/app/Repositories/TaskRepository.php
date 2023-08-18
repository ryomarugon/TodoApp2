<?php

namespace App\Repositories;
use App\Models\Task;

class TaskRepository
{
    public function getAllTasks()
    {
        return Task::orderBy('order', 'asc')->get();
    }

    public function createTask($data)
    {
        $task = new Task();
        $task->name = $data['name'];
        $task->tags = $data['tags'];
        $task->status = $data['status'];
        $task->order = $data['order'];
        $task->save();
        return $task;
    }

    public function updateTaskOrder($tasksData)
    {
        foreach ($tasksData as $taskData) {
            $task = Task::find($taskData['id']);
            $task->status = $taskData['status'];
            $task->order = $taskData['order'];
            $task->save();
        }
    }
}


?>