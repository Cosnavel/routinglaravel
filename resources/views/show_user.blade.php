@extends('layouts.app')

@section('content')
<div class="ui grid">
    @foreach ($users as $user)
    <div class="four wide column">
        <div class="ui card">
            <div class="content">
                <p class="header"> {{$user['name']}}</p>
                <div class="description">{{$user['email']}} <br> Alter: {{$user['age']}}</div>
            </div>
            @isset($user['phone'])
            <div class="extra content">
                <a>
                    <i class="phone icon"></i>
                    {{$user['phone']}}
                </a>
            </div>



            @endisset
        </div>

    </div>

    @endforeach

</div>
@endsection