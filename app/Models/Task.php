<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    static public function allTasks()
    {
        return Task::where('status', '<>', 'completed')->get();
    }

    public function allTasksTiedToUser()
    {
        return Task::where('status', '<>', 'completed')
            ->where('assigned', '=', Auth::user()->id);
    }
}
