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
    <h1>タスクの編集</h1>

    <form action='{{ url('/todos',$todo->id) }}' method="post">
      {{csrf_field()}}
      {{ method_field('patch')}}
      <div class="form-group">
        <label >新しいタスクを入力してください</label>
        <input type="text" name="body"class="form-control" value="{{ $todo->body }}">
      </div>
      <button type="submit" class="btn btn-primary">更新する</button>
    </form>

    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  </body>
</html>
