<?php

namespace App\Http\Controllers;

use App\Api\Swagger\User\UserSwagger;
use App\Http\Requests\StoreRequest;
use App\Http\Requests\UpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
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

    public function show($id): JsonResponse
    {
        return (new UserService($this->user))->show($id);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $payload = $request->validated();

        return (new UserService($this->user))->store($payload);
    }

    public function update(UpdateRequest $request, $id)
    {
        $payload = $request->validated();

        return (new UserService($this->user))->update($payload, $id);
    }

    public function destroy($id): JsonResponse
    {
        return (new UserService($this->user))->delete($id);
    }
}
