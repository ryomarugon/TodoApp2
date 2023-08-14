<?php

namespace App\Repositories;
use App\Models\Task;

class TaskRepository {
    public static function findAllTasks(){
        $tasks = Task::all();
        return response()->json($tasks);
    }
}

?>