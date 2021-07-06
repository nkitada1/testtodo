<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public static function findAndUserId($id, $user_id)
    {
        return self::where('id', $id)
            ->where('user_id', $user_id)
            ->first();
    }
}
