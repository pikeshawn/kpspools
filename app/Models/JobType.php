<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
