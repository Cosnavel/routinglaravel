@extends('layouts.app')

@section('content')

@if(session($key ?? 'status'))
<div class="ui positive message ">
    <div class="header">
        {{session($key ?? 'status')}}
    </div>
</div>
@endif

@if($errors->any())
<div class="ui negative message">
    <div class="header">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </ul>

    </div>
</div>
@endif





@if (isset($item))

<div class="tree">
    <ul>
        <li>
            <div class="tree-child">
                <div class="ui card">
                    <div class="content">
                        <div class="right floated meta">
                            @if($item->gender == "Male")
                            <i class="right large blue inverted circular mars icon"></i>
                            @else
                            <i class="right large red inverted circular venus icon"></i>
                            @endif

                        </div>

                        <a class="header">{{$item->name}}</a>

                    </div>
                    <div class="content">
                        <div class="description">
                            <i class="birthday cake icon"></i>
                            <span class="date">born: {{$item->birth->format('d.m.Y')}}</span>

                        </div>
                        <div class="description">
                            <span class="date">current age: {{$item->birth->diffInYears(now())}}</span>

                        </div>
                    </div>
                    @if($item->child_count < 2)
                    <x-open_modal_button :item="$item" />
            @endif

            </div>
</div>

<x-add_parent_modal :item="$item" />

<ul>
    @each('tree.child', $item->childRecursive, 'item')
</ul>



</li>
</ul>

</div>
@else
<button class="circular ui icon button" onclick="$('.ui.modal').modal('show'); $('#standard_calendar').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
    date: function(date,settings) {
        if(!date) return '';
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        return year + '-' + month + '-' + day;
    }
}}); $('.selection.dropdown').dropdown();">

    Start Tree
</button>


<div class="ui modal">
    <i class="close icon"></i>

    <div class="content">
        <div class="description">
            <div class="ui header">add yourself</div>
        </div>
        <form class="ui form" method="POST" action="/" autocomplete="off">
            @csrf

            <div class="field">
                <label>Name</label>
                <input type="text" name="name" placeholder="name" required>
            </div>
            <div class="ui calendar" id="standard_calendar">
                <label>birth</label>
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="Date/Time" name="birth" required>
                </div>
            </div>

            <div class="field">
                <label>gender</label>
                <div class="ui selection dropdown">
                    <input type="hidden" name="gender" required>
                    <i class="dropdown icon"></i>
                    <div class="default text">gender</div>
                    <div class="menu">
                        <div class="item" data-value="Male">Male</div>
                        <div class="item" data-value="Female">Female</div>
                    </div>
                </div>
            </div>


            <button class="ui button" type="submit">Submit</button>
        </form>


    </div>

</div>
@endif

@endsection