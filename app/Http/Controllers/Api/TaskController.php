<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $filters = [];

    // Валидация due_date
    if ($request->has('due_date') && !empty($request->due_date)) {
        Validator::make($request->all(), [  // Валидируем due_date
            'due_date' => 'date'
        ]);
        $filters['due_date'] = $request->due_date;
    }

    // Валидация status
    if ($request->has('status') && !empty($request->status)) {
        Validator::make($request->all(), [  // Валидируем status
            'status' => 'in:pending,in_progress,completed'
        ]);
        $filters['status'] = $request->status;
    }

    $tasks = auth()->user()->tasks()
                ->applyFilters($filters) 
                ->get();

    return response()->json($tasks);
    }

    public function store(Request $request) :JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user(); // Получаем объект пользователя
        $task = $user->tasks()->create($validator->validated());

        return response()->json($task, 201);
    }

    public function show(Task $task) :JsonResponse
    {

        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($task);
    }

    public function update(Request $request, Task $task) :JsonResponse
    {

        //Проверка
        if ($task->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $task->update($validator->validated());

        return response()->json($task);
    }

    public function destroy(Task $task) :JsonResponse
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
}
