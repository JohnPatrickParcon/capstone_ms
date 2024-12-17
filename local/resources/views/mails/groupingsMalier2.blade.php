@component('mail::message')
Good Day,

This email is to inform you that a Capstone Groupings has been Created.

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