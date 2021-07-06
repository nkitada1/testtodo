<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Validation\Rule;

class EditTask extends CreateTask
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = parent::rules();

        // 期限：本日以降のルールを除外
        $rule['due_date'] = 'required|date';

        $status_rule = Rule::in(array_keys(Task::STATUS));

        return $rule + [
            'status' => 'required|' . $status_rule,
        ];
    }

    public function attributes()
    {
        $attributes = parent::attributes();

        return $attributes + [
            'status' => '状態',
        ];
    }

    public function messages()
    {
        $messages = parent::messages();

        $status_label = array_map(function($item) {
            return $item['label'];
        }, Task::STATUS);

        $status_label = implode('、', $status_label);

        return $messages + [
            'status.in' => ':attribute には ' . $status_label . ' のいずれかを指定してください。'
        ];
    }
}
