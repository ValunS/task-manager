<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Создание пользователя для аутентификации
        $this->user = User::factory()->create();

        // Аутентификация пользователя
        $this->actingAs($this->user, 'sanctum');
    }

    public function test_can_get_all_tasks()
    {
        // Создание тестовых задач
        $tasks = $this->user->tasks()->createMany([
            ['title' => 'Task 1', 'due_date' => now()->addDays(2)->format('Y-m-d'), 'status' => 'pending'],
            ['title' => 'Task 2', 'due_date' => now()->addDays(5)->format('Y-m-d'), 'status' => 'in_progress'],
        ]);

        // Отправка GET-запроса на /api/tasks
        $response = $this->getJson('/api/tasks');

        // Проверки
        $response->assertStatus(200); 
        $response->assertJsonCount(2); // Проверяем количество задач в ответе
    }

    public function test_can_create_task()
    {
        // Данные для новой задачи
        $taskData = [
            'title' => 'New Task',
            'description' => 'Test description',
            'due_date' => now()->addWeek()->format('Y-m-d'), // Форматируем дату
            'status' => 'pending' 
        ];

        // Отправка POST-запроса на /api/tasks
        $response = $this->postJson('/api/tasks', $taskData);

        // Проверки
        $response->assertStatus(201); 
        $response->assertJsonFragment($taskData); // Проверяем наличие данных в ответе

        // Дополнительная проверка: убедитесь, что задача создана в базе данных
        $this->assertDatabaseHas('tasks', $taskData); 
    }

    // ... другие тесты ... 
}

