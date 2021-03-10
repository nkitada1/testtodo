<!doctype html>
<html lang="ja">
  <head>
    <title>Todoリスト</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  </head>
  <body>
    <div class="container">

      <!-- タスクの登録フィールド -->
      <h1>タスクの追加</h1>
      <form action='{{ url('/todos')}}' method="post">
      {{csrf_field()}}
      <div class="form-group">
        <label >追加するタスクを入力して下さい。</label>
        <input type="text" name="body"class="form-control" placeholder="todo list">
      </div>
      <button type="submit" class="btn btn-primary">登録</button>  </form>

      <!-- タスクの一覧フィールド -->
      <h1>Todoリスト</h1>
      <table class="table table-striped">
      <tbody>
      @foreach ($todos as $todo)
        <tr>
        <td>{{$todo->body}}</td>
        <td><form action="{{ action('App\Http\Controllers\TodosController@edit', $todo) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('get') }}
            <button type="submit" class="btn btn-primary">編集</button>
        </form>
        </td>

	<!-- 完了ボタン -->
	<td><form action="{{url('/todos', $todo->id)}}" method="post">
	    {{ csrf_field() }}
	    {{ method_field('delete') }}
	    <button type="submit" class="btn btn-danger">完了</button>
	</form>
	</td>
      </tr>
    @endforeach
  </table>
</div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </body>
</html>
