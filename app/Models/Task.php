<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function list()
    {
        return $this->belongsTo(ToDoList::class, 'task_id');
    }

    public function user()
    {
        $user = User::find($this->user_id);
        return $user;
    }
}
