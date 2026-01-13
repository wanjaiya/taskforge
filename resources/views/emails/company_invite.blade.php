@extends('layouts.emails')

@section('content')
    <p
        style="font-family:Helvetica Neue, sans-serif;font-size: 28px;line-height:1.6;font-weight:normal;margin:0 0 30px;padding:0;color:#F67910;text-align:center;">
        Account Invite
    </p>
    <hr class="line-footer">
    <p class="bigger-bold" style="font-family: Helvetica Neue, sans-serif;text-align:center;font-size: 22px;">Hello</p>


    <p
        style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">
        You have been invited to access the {{ $company }} dashboard on {{ config('app.name') }}. Click the link below
        to accept the invitation:.</p>


    style="font-family: Helvetica Neue, sans-serif;margin: 30px 0 30px; text-align:center;color:#23245F;font-size:16px;font-weight: bold;">
    <a href="{{ $inviteLink }}"
        style="background:#13556f;
              padding: 10px 15px;
              color: #fff;
              font-size: 18px;
              text-decoration: none;">Accept
        Invite
    </a>
    </p>
    <p
        style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">
        If you did not expect this email, please ignore it.</p>

    <p
        style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">
        Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
