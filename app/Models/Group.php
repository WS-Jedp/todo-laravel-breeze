<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function lists()
    {
        return $this->hasMany(ToDoList::class, 'group_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_groups', 'user_id', 'group_id');
    }
}
