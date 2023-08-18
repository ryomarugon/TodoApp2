<?php
namespace App\Services;

use App\Models\Task;
use App\Repositories\TaskRepository;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks()
    {
        return $this->taskRepository->getAllTasks();
    }

    public function createTask($data)
    {
        return $this->taskRepository->createTask($data);
    }

    public function updateTaskOrder($tasksData)
    {
        // ここでタスク情報の更新を行うロジックを記述
        $this->taskRepository->updateTaskOrder($tasksData);
    }
}
?>