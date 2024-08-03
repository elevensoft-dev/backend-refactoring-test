<?php

namespace App\Http\Controllers;

use App\Api\Swagger\User\UserSwagger;
use App\Http\Requests\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private User $user;

    use UserSwagger;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        return $this->user->get();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function store(StoreRequest $request)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        return $this->user->create($data);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->only([
            'name',
            'email',
            'password',
        ]);

        $user->update($data);

        return $user;
    }

    public function destroy(User $user)
    {
        $user->delete();

        return $user;
    }
}
