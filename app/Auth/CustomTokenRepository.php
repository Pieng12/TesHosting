<?php

namespace App\Auth;

use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class CustomTokenRepository extends DatabaseTokenRepository
{
    /**
     * Create a new token for the user.
     *
     * @return string
     */
    public function createNewToken()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
