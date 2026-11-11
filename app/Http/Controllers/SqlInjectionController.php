<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SqlInjectionController extends Controller
{
    public function login(Request $request, $type)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if ($type === 'vulnerable') {
            // Vulnerable SQL query prone to authentication bypass via comments (-- )
            $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
            $users = DB::select($query);
            $user = !empty($users) ? $users[0] : null;

        } elseif ($type === 'secure') {
            // Secure query using Eloquent and password_verify
            $foundUser = User::where('email', $email)->first();

            if ($foundUser && password_verify($password, $foundUser->password)) {
                $user = $foundUser;
            } else {
                $user = null;
            }

        } else {
            abort(400, 'Invalid login type.');
        }

        if ($user) {
            return response()->json(['message' => 'Login successful', 'user' => $user]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}