<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJobRate extends Model
{
    use HasFactory;

    protected $table = 'user_job_rates';
    protected $guarded = [];


    public static function getRate($servicemanId, $jobTypeId)
    {
        $ujr = UserJobRate::where('serviceman_id', $servicemanId)
        ->where('job_type_id', $jobTypeId)->first();
        return $ujr->rate;
    }

}
