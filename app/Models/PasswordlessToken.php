<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordlessToken extends Model
{
    use HasFactory;

    protected $table = "user_tokens";
    protected $fillable = [
        'token',
        'created_at',
        'user_id',
    ];
    protected $dates = ['created_at', 'expires_at'];
    /**
     * Is not used and not expired.
     *
     * @return bool
     */
    public function isValid()
    {
        return ! $this->isExpired();
    }
    /**
     * Is token expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return false;
//        return $this->created_at
//            ->diffInMinutes(Carbon::now()) > (int)config("passwordless.expire_in");
    }
    /**
     * Ignore the updated_at column.
     *
     * @param mixed $value Update date
     *
     * @return null
     */
    public function setUpdatedAt($value)
    {
    }
    /**
     * Token belongs to auth user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
