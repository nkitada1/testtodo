<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * 状態定義
     */
    public const STATUS = [
        1 => ['label' => '未着手', 'class' => 'label-danger'],
        2 => ['label' => '着手中', 'class' => 'label-info'],
        3 => ['label' => '完了', 'class' => ''],
    ];

    protected $fillable = ['title', 'due_date', 'status'];

    /**
     * 状態のラベル
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $status = $this->attributes['status'];

        if (!isset(self::STATUS[$status])) {
            return '';
        }

        return self::STATUS[$status]['label'];
    }

    /**
     * 状態を表すHTMLクラス
     * @return string
     */
    public function getStatusClassAttribute(): string
    {
        // 状態値
        $status = $this->attributes['status'];

        // 定義されていなければ空文字を返す
        if (!isset(self::STATUS[$status])) {
            return '';
        }

        return self::STATUS[$status]['class'];
    }

    /**
     * 整形した期限日
     * @return string
     */
    public function getFormattedDueDateAttribute(): string
    {
        return Carbon::createFromFormat('Y-m-d', $this->attributes['due_date'])
            ->format('Y/m/d');
    }

    public static function batfindAndUserId($id, $user_id)
    {
        return self::join('folders','tasks.folder_id','=','folders.id')
            ->where('tasks.id', $id)
            ->where('folders.user_id', $user_id)
            ->first();
    }

    public static function findAndUserId($id, $user_id)
    {
        $task = self::find($id);
        if (is_null($task)) {
            return;
        }

        $folder = Folder::findAndUserId($task->folder_id, $user_id);
        if (isset($folder)) {
            return $task;
        }
    }
}
