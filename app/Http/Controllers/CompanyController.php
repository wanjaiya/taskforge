<?php

namespace App\Http\Controllers;

use App\Mail\CompanyInviteMail;
use App\Models\Company;
use App\Models\CompanyInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    //
    public function store(Request $request)
    {



        $emails = array_map('trim', explode(',', $request->input('emails')));

        // Validate each email
        $data = [
            'emails' => $emails,
            'name' => $request->input('name'),
        ];

        // Define validation rules
        $rules = [
            'emails.*' => 'email',    // each email must be valid
            'name' => 'required|string|max:255',

        ];

        // Run validation
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        if ($request->file('logo')) {

            $filename = 'logo_' . time() . '.' . $request->logo->getClientOriginalExtension();
            $request->logo->move(public_path('images/logos'),  $filename);
        }

        $company = new Company();
        $company->name = $request->name;
        $company->logo = $filename;
        $company->owner_id = Auth::user()->id;

        $company->save();

        foreach ($emails as $email) {

            $token = Str::random(40);

            CompanyInvite::create([
                'email' => $email,
                'token' => $token,
                'expires_at' => Carbon::now()->addDays(7),
            ]);

            $inviteLink = route('company.invite.accept', ['token' => $token]);

            Mail::to($email)->send(new CompanyInviteMail($inviteLink, $company->name));
        }


        return back()->with('success', 'Company and invites create successfully');
    }
}
