<?php

namespace App\Services;

use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use App\Models\Folder;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function createTask(int $folder_id, CreateTask $create_task_request): void
    {
        $current_folder = Folder::findAndUserId($folder_id, Auth::user()->id);
        if (is_null($current_folder)) {
             abort(404);
        }

        $task = new Task();
        // $task->title = $create_task_request->title;
        // $task->due_date = $create_task_request->due_date;

        $task->fill($create_task_request->all());
        $current_folder->tasks()->save($task);

    }

    public function updateTask(int $task_id, EditTask $edit_task_request): void
    {
        $task = $this->findTaskAndUserId($task_id, Auth::user()->id);
        if (is_null($task)) {
            abort(404);
        }

        $task->fill($edit_task_request->all());
        $task->save();
    }

    public function deleteTask(int $task_id)
    {
        $task = $this->findTaskAndUserId($task_id, Auth::user()->id);
        if (is_null($task)) {
            abort(404);
        }

        $task->delete();
    }

    public function getTask(int $task_id)
    {
        $user = Auth::user();
        if (is_null($user)) {
            abort(404);
        }

        $task = $this->findTaskAndUserId($task_id, $user->id);
        if (is_null($task)) {
            abort(404);
        }

        return $task;
    }

    public function findTaskAndUserId($task_id, $user_id)
    {
        $task = Task::find($task_id);
        if (is_null($task)) {
            return;
        }

        $folder = Folder::findAndUserId($task->folder_id, $user_id);
        if (is_null($folder)) {
            return;
        }

        return $task;
    }
}
