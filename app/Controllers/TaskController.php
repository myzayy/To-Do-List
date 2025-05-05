<?php

namespace App\Controllers;

use App\Models\Task;

class TaskController
{
    private Task $task;

    public function __construct()
    {
        $this->task = new Task();
    }

    public function index()
    {
        $tasks = $this->task->all();
        require_once __DIR__ . '/../Views/list.php';
    }

    public function store()
    {
        $this->task->create($_POST['title'], $_POST['description']);
        header('Location: /');
    }

    public function edit(int $id)
    {
        $task = $this->task->get($id);
        require_once __DIR__ . '/../Views/edit.php';
    }

    public function update(int $id)
    {
        $this->task->update($id, $_POST['title'], $_POST['description']);
        header('Location: /');
    }

    public function delete(int $id)
    {
        $this->task->delete($id);
        header('Location: /');
    }
}
