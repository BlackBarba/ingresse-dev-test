<?php

namespace App\Repositories;
use App\Models\User as UserModel;
use App\Http\Requests\User as UserRequest;

class User {
    public static function save (UserRequest $request, $user = null) {
        if ($user) {
            $user = UserModel::findOrFail($user);
        } else {
            $user = new UserModel;
        }

        $validated = $request->validated();

        foreach ($validated as $key => $value) {
            $user->{$key} = $value;
        }

        return $user->save() ? $user : false;
    }
    
    public static function delete ($user) {
        $user = UserModel::findOrFail($user);

        return $user->delete() ? $user : false;
    }
}