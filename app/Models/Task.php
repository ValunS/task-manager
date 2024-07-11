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
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeByDueDate($query, $dueDate)
    {
        if ($dueDate) {
            return $query->whereDate('due_date', $dueDate);
        }
        return $query;
    }
}
