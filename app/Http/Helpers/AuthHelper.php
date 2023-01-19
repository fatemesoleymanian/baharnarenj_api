<?php

use App\Exceptions\CustomException;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

if (!function_exists('currentUser')) {
    /**
     * @return Authenticatable|User|Admin
     * @throws CustomException
     */
    function currentUser(): Authenticatable|User|Admin
    {
        $currentUser = auth()->user();
        if (exists($currentUser)) {
            return $currentUser;
        }
        throw new CustomException('شما اجازه دسترسی به این مورد را ندارید');
    }
}
