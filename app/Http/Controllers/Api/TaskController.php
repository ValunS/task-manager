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
        $validator = Validator::make($request->all(), [
            'due_date' => 'date',
            'status' => 'in:pending,in_progress,completed',
        ]);

        $tasks = auth()->user()->tasks();

        if ($request->has('due_date') && !empty($request->input('due_date'))) { 
            $tasks->whereDate('due_date', $request->input('due_date'));
        }
    
        if ($request->has('status') && !empty($request->input('status'))) { 
            $tasks->where('status', $request->input('status'));
        }

        return response()->json($tasks->get());
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
