@component('mail::message')
You have been invited as an adviser to the CLSU Capstone Management System. 

Please click the “Register” button to register your email address. 
Thank You.

@component('mail::button', ['url' => $user_data["url"], 'color' => 'success'])
    REGISTER
@endcomponent

@endcomponent