<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

use App\Models\User;
use App\Models\Customer;

use Ulid\Ulid;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $ulid = Ulid::generate();
        $user = new User();
        $user->ulid = $ulid;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->role_id = User::CUSTOMER_ROLE;
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        if ($user->role_id === User::CUSTOMER_ROLE) {
            $user_info = new Customer();
            $user_info->user_id = $user['id'];
            $user_info->gender = $request['gender'];
            $user_info->birthday = $request['birthday'];
            $user_info->save();
        }

        return $this->customResponse('Successfully Registered.', [], Response::HTTP_CREATED);
    }
}
