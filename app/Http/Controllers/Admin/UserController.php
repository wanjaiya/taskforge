<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\AccountVerification;
use Mail;

class UserController extends Controller
{
    //

    public function index(Request $request)
    {
       
        $users = User::with('roles')->latest()->paginate(20);
        $roles = Role::all();

        foreach($users as $user){
            $user->role_names = $user->roles->pluck('name')->toArray();
        }

        

        return view('admin.users.index', compact('users', 'roles'));
    }

 

    public function store(Request $request)
    {


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
        ]);


        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->roles()->attach($validated['role']);

        $token = AccountVerification::createTokenForUser($user->id);


         // Send verification email
        Mail::send('emails.account-verification', ['name' => $user->name, 'token' => $token->token], function ($message) use ($user) {
            $message->from('info@jalebi.com', 'The '. config('app.name').' Team');
            $message->to($user->email, $user->name);
            $message->subject('Verify Your '. config('app.name').' Account');
        });
      

        return redirect()->route('users.index')->with('status', 'User created successfully!');
    }

    public function verify(Request $request, User $user)
    {
        
        $user->email_verified_at = now();
        $user->save();

        return redirect()->route('users.index')->with('status', 'User verified successfully!');
    }

 

    public function update(Request $request, User $user)
    {
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required',
        ]);


        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => ($request->input('password')) ? bcrypt($validated['password']) : $user->password,
        ]);

        $user->roles()->sync([$validated['role']]);

        return redirect()->route('users.index')->with('status', 'User updated successfully!');
    }

    public function destroy(Request $request, User $user)
    {
        

        $user->update(['deleted_at' => now()]);

         
        return redirect()->route('users.index')->with('status', 'User deleted successfully!');

    }
}
