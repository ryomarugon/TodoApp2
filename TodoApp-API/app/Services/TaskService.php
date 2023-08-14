<?php
namespace App\Services;

use App\Repositories\TaskRepository;

class taskService
{
    // フルーツ一覧取得
    public function getAllTasks()
    {
        //fruitRepositoryのfindAllFruitを呼び出し、取得
        $fruits = TaskRepository::findAllTasks()->get();
        return $fruits;
    }

}