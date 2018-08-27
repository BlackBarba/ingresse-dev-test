<?php

namespace App\Repositories;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

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

        return $user->save() ? $user : false;
    }
    
    public static function delete ($user) {
        $user = UserModel::findOrFail($user);

        return $user->delete() ? $user : false;
    }
}