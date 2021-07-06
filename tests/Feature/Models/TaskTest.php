<?php

namespace Tests\Feature\Models;

use App\Http\Requests\CreateTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    // テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    /**
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    private $user;

    /**
     * 各テストメソッドの実行前に呼ばれる
     */
    public function setUp() :void
    {
        parent::setUp();

        // テストケース実行前にフォルダデータを作成する
        $this->seed('UsersTableSeeder');
        $this->seed('FoldersTableSeeder');
        $this->user = User::factory()->create();
        $this->user->id = 1;

        // $this->user = DB::table('users')->first();
    }

    /**
     * 正常完了 タイトル：1文字
     * @test
     */
    public function create_min_title()
    {
        $response = $this->actingAs($this->user)->post('/folders/1/tasks/create', [
            'title' => 'a',
            'due_date' => Carbon::today()->format('Y/m/d'), // 不正なデータ（昨日の日付）
        ]);

        $response->assertSessionHasNoErrors();
    }

    /**
     * 正常完了 タイトル：10文字
     * @test
     */
    public function create_max_title()
    {
        $response = $this->actingAs($this->user)->post('/folders/1/tasks/create', [
            'title' => '0123456789',
            'due_date' => Carbon::today()->format('Y/m/d'), // 不正なデータ（昨日の日付）
        ]);

        $response->assertSessionHasNoErrors();
    }

    /**
     * 期限日が日付ではない場合はバリデーションエラー
     * @test
     */
    public function create_none_title()
    {
        $response = $this->actingAs($this->user)->post('/folders/1/tasks/create', [
            'due_date' => Carbon::today()->format('Y/m/d'),
        ]);

        $response->assertSessionHasErrors([
            'title' => 'タイトル は必須入力です。',
        ]);

    }

    /**
     * 期限日が日付ではない場合はバリデーションエラー
     * @test
     */
    public function none_due_date()
    {
        $response = $this->actingAs($this->user)->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 は必須入力です。',
        ]);
    }

    /**
     * 期限日が日付ではない場合はバリデーションエラー
     * @test
     */
    public function due_date_should_be_date()
    {
        $response = $this->actingAs($this->user)->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_date' => 123, // 不正なデータ（数値）
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 には日付を入力してください。',
        ]);
    }

    /**
     * 期限日が過去日付の場合はバリデーションエラー
     * @test
     */
    public function due_date_should_not_be_past()
    {
        $response = $this->actingAs($this->user)->post('/folders/1/tasks/create', [
            'title' => 'Sample task',
            'due_date' => Carbon::yesterday()->format('Y/m/d'), // 不正なデータ（昨日の日付）
        ]);

        $response->assertSessionHasErrors([
            'due_date' => '期限日 には今日以降の日付を入力してください。',
        ]);
    }

    /**
     * 状態が定義された値ではない場合はバリデーションエラー
     * @test
     */
    public function status_should_be_whitin_defined_numbers()
    {
        // $this->seed('TasksTableSeeder');

        $response = $this->actingAs($this->user)->post('/folders/1/tasks/1/edit', [
            'title' => 'Sample task',
            'due_date' => Carbon::today()->format('Y/m/d'),
            'status' => 999,
        ]);

        $response->assertSessionHasErrors([
            'status' => '状態 には 未着手、着手中、完了 のいずれかを指定してください。',
        ]);
    }
}
