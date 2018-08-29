<?php

namespace App\Repositories;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class User {
    public static function save ($params, $user = null) {
        if ($user) {
            $user = UserModel::findOrFail($user);
        } else {
            $user = new UserModel;
        }

        if (isset($params['password'])) {
            $params['password'] = Hash::make($params['password']);
        }

        foreach ($params as $key => $value) {
            $user->{$key} = $value;
        }

        if ($user->save()) {
            Cache::put('user:'.$user->id, $user->toArray(), 1);
            Cache::tags('users')->flush();

            return $user;
        } else {
            return false;
        }
    }
    
    public static function delete ($user) {
        $user = UserModel::findOrFail($user);

        if ($user->delete()) {
            Cache::tags('users')->flush();
            
            return $user;
        } else {
            return false;
        }
    }
}