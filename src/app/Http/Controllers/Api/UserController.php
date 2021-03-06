<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User as UserRequest;
use App\Repositories\User as UserRepository;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\Paginable;

class UserController extends Controller
{
    use Paginable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = $this->getPerPage();
        $page = $this->getPage();
        
        return Cache::tags(['users', 'page:'.$page, 'perPage:'.$perPage])->remember('pagination', 1, function () use ($perPage) {
            return User::paginate($perPage);
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\User  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        return response()->create(UserRepository::save($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Cache::remember('user:'.$id, 1, function () use ($id) {
            return User::findOrFail($id);
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        return response()->update(UserRepository::save($request->validated(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->delete(UserRepository::delete($id));
    }
}
