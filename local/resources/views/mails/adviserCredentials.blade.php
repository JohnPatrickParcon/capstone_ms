@component('mail::message')
Thank you for accepting the invitation.

Here is your CMS credentials,<br><br>
Email Address: <u>{{ $user_data["email"] }}</u><br>
Temporary Password: <u>{{ $user_data["password"] }}</u><br>


System Site: <a href={{$user_data['site']}}>Click Here</a><br>


@endcomponent