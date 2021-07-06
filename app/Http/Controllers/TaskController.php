<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTask;
use App\Http\Requests\EditTask;
use App\Models\Folder;
use App\Models\Task;
use App\Services\FolderService;
use App\Services\TaskService;

class TaskController extends Controller
{
    private $folder_service;
    private $task_service;

    /**
     * TaskController constructor.
     * @param FolderService $folder_service
     * @param TaskService $task_service
     */
    public function __construct(FolderService $folder_service, TaskService $task_service)
    {
        $this->folder_service = $folder_service;
        $this->task_service = $task_service;
    }

    public function index(int $id)
    {
        $folders = $this->folder_service->getUserFolders();
        $tasks = $this->folder_service->getFolderInTasks($id);

        return view('tasks/index', [
            'folders' => $folders,
            'current_folder_id' => $id,
            'tasks' => $tasks,
        ]);
    }

    public function showCreateForm(int $id)
    {
        return view('tasks/create', [
            'folder_id' => $id
        ]);
    }

    public function create(int $id, CreateTask $request)
    {
        $this->task_service->createTask($id, $request);

        return redirect()->route('tasks.index', [
            'id' => $id,
        ]);
    }

    public function showEditForm(int $id, int $task_id)
    {
        $task = $this->task_service->getTask($task_id);

        return view('tasks/edit',[
            'task' => $task,
        ]);
    }

    public function edit(int $id, int $task_id, EditTask $request)
    {
        $this->task_service->updateTask($task_id, $request);

        return redirect()->route('tasks.index', [
            'id' => $id,
        ]);
    }

    /**
     *  タスクの削除
     *
     * @param int $id
     * @param int $task_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(int $id, int $task_id)
    {
        $this->task_service->deleteTask($task_id);

        return redirect()->route('tasks.index', [
            'id' => $id,
        ]);
    }
}
