<?php

namespace App\Traits;

use App\Models\PasswordlessToken;
use App\Models\UserToken;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait Passwordless
{
    /**
     * Validate attributes.
     *
     * @param  string  $token  PasswordlessToken
     *
     * @throws InvalidTokenException
     */
    public function validateToken($token)
    {
        if (! $this->isValidToken($token)) {
            throw new InvalidTokenException(trans('passwordless.invalid_token'));
        }
    }

    /**
     * Validate attributes.
     *
     * @param  string  $token  PasswordlessToken
     * @return bool
     */
    public function isValidToken($token)
    {
        return true;
        //        if (!$token) {
        //            return false;
        //        }
        //        /**
        //         * @var $tokenModel PasswordlessToken
        //         */
        //        $tokenModel = $this->tokens()->where('token', $token)->first();
        //        return $tokenModel ? $tokenModel->isValid() : false;
    }

    /**
     * Generate a token for the current user.
     *
     * @param  bool  $save  Generate token and save it.
     * @param  int  $job_id  Id of the job so that a token is only tied to that job not some other contractors job.
     * @param  string  $job_step
     * @param  string  $job_task_step
     * @param  string  $sub_step
     * @param  string  $type  what the token was created for, email, text, etc.
     * @param  null  $taskId
     * @return UserToken|null
     */
    //    public function generateToken($save = false)
    public function generateToken(
        $user_id
    ) {
        $token = new UserToken;
        $attributes = [
            'user_id' => $user_id,
            'token' => Str::random(16),
            'created_at' => time(),
        ];
        //        $token = App::make(PasswordlessToken::class);
        $token->fill($attributes);
        try {
            $token->save();
        } catch (\Exception $e) {
            Log::error('Error Saving User Token: '.$e->getMessage());

            return null;
        }

        return $token->token;
    }

    /**
     * User tokens relation.
     *
     * @return mixed
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(PasswordlessToken::class, 'user_id', 'id');
    }

    /**
     * Identifier name to be used with token.
     *
     * @return string
     */
    protected function getIdentifierKey()
    {
        return 'email';
    }
}
