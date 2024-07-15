<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'status', 'due_date'];

    // Отношение к модели User (один ко многим)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Области видимости (scopes) для поиска
    public function scopeByStatus($query, $status = null)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByDueDate($query, $dueDate = null)
    {
        if ($dueDate) {
            return $query->whereDate('due_date', $dueDate);
        }
        return $query;
    }

    public function scopeApplyFilters($query, array $filters)
    {
        //  Фильтрация по дате выполнения
        if (isset($filters['due_date']) && !empty($filters['due_date'])) {
            $query->byDueDate($filters['due_date']);
        }

        // Фильтрация по статусу
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        //  Здесь можно легко добавить другие фильтры в будущем

        return $query;
    }
}
