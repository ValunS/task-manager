<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    // Проверка связи задачи с пользователем
    public function test_task_belongs_to_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $task->user); // Отношение - экземпляр User
        $this->assertEquals($user->id, $task->user->id); // ID совпадают 
    }

    // Проверка атрибутов модели Task
    public function test_task_model_has_expected_attributes()
    {
        $task = Task::factory()->create([
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'due_date' => now()->addDays(3),
        ]);

        // Проверяем значения атрибутов 
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('Test Description', $task->description);
        $this->assertEquals('pending', $task->status);
        $this->assertEquals(now()->addDays(3)->format('Y-m-d'), $task->due_date->format('Y-m-d'));
    }

    // Тест фабрики Task
    public function test_can_create_task_with_factory()
    {
        $task = Task::factory()->create(); 
        $this->assertDatabaseHas('tasks', ['id' => $task->id]); // Задача создана в БД
    }

    // Тест scope byDueDate
    public function test_scope_by_due_date()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'due_date' => now()->subDay()]);
        $taskToday = Task::factory()->create(['user_id' => $user->id, 'due_date' => now()]);
        Task::factory()->create(['user_id' => $user->id, 'due_date' => now()->addDay()]);

        // Получаем задачи на сегодня
        $tasks = Task::byDueDate(now()->toDateString())->get();
        $this->assertCount(1, $tasks); // 1 задача 
        $this->assertEquals($taskToday->id, $tasks->first()->id); // Правильная задача
    }

    // Тест scope byStatus
    public function test_scope_by_status()
    {
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'status' => 'pending']);
        $taskInProgress = Task::factory()->create(['user_id' => $user->id, 'status' => 'in_progress']);
        Task::factory()->create(['user_id' => $user->id, 'status' => 'completed']);

        // Получаем задачи "in progress"
        $tasks = Task::byStatus('in_progress')->get();
        $this->assertCount(1, $tasks); // 1 задача
        $this->assertEquals($taskInProgress->id, $tasks->first()->id); // Правильная задача
    }

    // Тест метода markAsCompleted
    public function test_set_status_to_completed()
    {
        $task = Task::factory()->create(['status' => 'pending']); 
        $task->status = 'completed';
        $task->save(); 
        $this->assertEquals('completed', $task->status); // Статус - completed 
    }
}
