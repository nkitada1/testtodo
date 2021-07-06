<?php

namespace App\Services;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Database\Eloquent\Collection;

class FolderService
{
    public function getUserFolders(): Collection
    {
        return Auth::user()->folders()->get();
    }

    public function findFolderByFolderId(int $id)
    {
        return Folder::find($id);
    }

    public function getFolderInTasks(int $id)
    {
        $folder = Folder::findAndUserId($id, Auth::user()->id);

        return ($folder) ? $folder->tasks()->get() : [];
    }
}
