@component('mail::message')
Good Day,

{{ $user_data["message"] }}

<br>Adviser : 
@foreach ($user_data["adviser"] as $item)
<u>{{ $item->name }}</u>,&nbsp;
@endforeach
<br>Panel : 
@foreach ($user_data["panel"] as $item)
<u>{{ $item->name }}</u>,&nbsp;
@endforeach
<br>Students : 
@foreach ($user_data["students"] as $item)
<u>{{ $item->name }}</u>,&nbsp;
@endforeach

@endcomponent