@extends('layouts.emails')

@section('content')

  <p style="font-family:Helvetica Neue, sans-serif;font-size: 28px;line-height:1.6;font-weight:normal;margin:0 0 30px;padding:0;color:#F67910;text-align:center;">Account Verification</p>
<hr class="line-footer">
<p class="bigger-bold" style="font-family: Helvetica Neue, sans-serif;text-align:center;font-size: 22px;">Hello {{ ucwords(strtolower($name)) ?: '' }},</p>


<p style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">
    Thank you for registering with us!.</p>

  <p style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">
      In order to complete your registration and log in, please enter the below "Activation Code".</p>


    <p style="font-family: Helvetica Neue, sans-serif;margin: 30px 0 30px; text-align:center;color:#23245F;font-size:16px;font-weight: bold;"> {{ $token }}</p>

    <p style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">The token expires in 24 hours.</p>

    <p style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">If you did not create an account, no further action is required.</p>

    <p style="font-family: Helvetica Neue, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#5A6166;text-align:center;">Best regards,<br>The Jobify Team</p>


@endsection
