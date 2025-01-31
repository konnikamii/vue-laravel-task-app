<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UserController extends Controller
{
    /**
     * Display the user's details.
     */
    public function getUserById(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            throw new NotFoundResourceException('User not found!', 404);
        }

        return response()->json($user);
    }

    /**
     * Display all users.
     */
    public function getAllUsers(Request $request)
    {
        $type = $request->json('type');
        if ($type == 'user_tasks') {
            $users = User::with('tasks')->get();

            return response()->json($users);
        } else {
            return response()->json(User::all());
        }
    }

    /**
     * Change the user's password.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();
        if (! $user) {
            throw new NotFoundResourceException('User not found!', 404);
        }

        // Check if the old password is correct
        if (! Hash::check($data['old_password'], $user->password)) {
            throw AuthException::invalidPassword();
        }

        // Update the user's password
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully!']);
    }

    /**
     * Authenticate.
     */
    public function auth(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            throw new NotFoundResourceException('User not found!', 404);
        }

        return response()->json('Authenticated successfully!');
    }
}
