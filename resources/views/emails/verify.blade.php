@php /** @var \App\Models\Subscription $subs */ @endphp
<h1>Спасибо, кликните по ссылке</h1>
<a href="http://blog/verify/{{$subs->token}}">{{$subs->token}}</a>
