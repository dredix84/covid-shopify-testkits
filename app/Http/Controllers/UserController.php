<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminOnly;
use App\Http\Requests\UserStoreRequest;
use App\Models\PickupLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class UserController extends Controller
{
    public function index(AdminOnly $request)
    {
        return Inertia::render(
            'ManageUsers',
            [
                'init-data' => [
                    'users'   => $this->getUsers($request),
                    'options' => [
                        'pickup_locations' => PickupLocation::list()->get()
                    ]
                ]
            ]
        );
    }

    public function getUsers(AdminOnly $request)
    {
        $per_page = $request->per_page ?? 25;

        return User::orderBy('order_number', 'desc')
            ->filter($request->all())
            ->paginate((int) $per_page);
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->all();

        if (!isset($data['password']) || blank($data['password'])) {
            unset($data['password'], $data['password_confirmation']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        /** @var User $user */
        $user = isset($data['_id']) ? User::find($data['_id']) : new User();
        $user->fill($data);
        $user->save();

        return $data;
    }


    public function destroy(AdminOnly $request, $userId)
    {
        /** @var User $user */
        $user = User::findOrFail($userId);
        $user->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }
}
