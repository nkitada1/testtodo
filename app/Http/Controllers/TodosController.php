<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;

/**
 * TODOタスク
 */
class TodosController extends Controller
{
    /**
     * TODOタスク一覧
     */
    public function index() {
        $todos = Todo::all();
        return view('todos.index')->with('todos',$todos);
    }

    /**
     * TODOタスク編集
     */
    public function edit(todo $todo) {
      return view('todos.edit')->with('todo',$todo);
    }

    /**
     * TODOタスク更新
     */
    public function update(Request $request,todo $todo) {
      $todo->body = $request->body;
      $todo->save();
      return redirect('/');
    }

    /**
     * TODOタスク登録
     */
    public function store(Request $request) {
        $todo = new Todo();
        $todo->body = $request->body;
        $todo->save();
        return redirect('/');
    }

    /**
     * TODOタスク完了
     */
    public function destroy(todo $todo) {
        $todo->delete();
        return redirect('/');
   }
}
