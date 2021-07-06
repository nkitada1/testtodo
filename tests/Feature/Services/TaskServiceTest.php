<?php

namespace Services;

use App\Services\TaskService;

use App\Http\Requests\CreateTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskServiceTest extends TestCase
{
    // テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    private $user;

    private $service;

    /**
     * 各テストメソッドの実行前に呼ばれる
     */
    public function setUp() :void
    {
        parent::setUp();

        // テストケース実行前にフォルダデータを作成する
        $this->seed('UsersTableSeeder');
        $this->seed('FoldersTableSeeder');
        $this->seed('TasksTableSeeder');

        $this->user = User::factory()->create();
        $this->user->id = 1;

        $this->service = new TaskService();
    }

    /**
     * @test
     *
     */
    public function get_task_has_none_user()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
        $task = $this->service->getTask(1);
    }

    /**
     * @test
     */
    public function get_task()
    {
        $this->actingAs($this->user);
        $task = $this->service->getTask(1);
        $this->assertSame($task->title, 'サンプルタスク1');
    }

    /**
     * @test
     */
    public function find_task_and_userid()
    {
        $task = $this->service->findTaskAndUserId(1, 1);
        $this->assertSame($task->title, 'サンプルタスク1');
    }

    /**
     * @test
     */
    public function find_task_and_userid_none_task()
    {
        $task = $this->service->findTaskAndUserId(999, 1);
        $this->assertNull($task);
    }

    /**
     * @test
     */
    public function find_task_and_userid_none_user()
    {
        $task = $this->service->findTaskAndUserId(1, 999);
        $this->assertNull($task);
    }

    /**
     * @test
     */
    public function create()
    {
        $this->actingAs($this->user);
        $request = new CreateTask([
            'title' => 'test',
            'due_date' => Carbon::today()->format('Y/m/d'),
            'status' => 0,
        ]);
        $this->service->createTask(1, $request);

        $task = $this->service->getTask(4);
        $this->assertSame($task->title, 'test');
    }
}
