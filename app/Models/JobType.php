<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobType extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship with servicemen
    public function servicemen(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_job_rates')
            ->withTimestamps();
    }
}
