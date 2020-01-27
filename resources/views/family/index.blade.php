@extends('layouts.app')


@section('content')






<h1>genealogy tree ( family tree ) </h1>



@if (session($key ?? 'status'))
<div class="ui positive message transition hidden">

    <div class="header">
        {{ session($key ?? 'status') }}
    </div>

</div>
@endif

@if ($errors->any())
<div class="ui negative message">

    <div class="header">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif





@if($tree->isNotEmpty())

@foreach ($tree as $item)

@if($item->parent == null)
<div class="tf-tree">
    <ul>
        <li>
            <span class="tf-nc">

                <div class="ui card">

                    <div class="content">

                        <div class="right floated meta">
                            @if($item->gender == 'Male')
                            <i class="right large blue inverted circular mars icon"></i>
                            @else
                            <i class="right large red inverted circular  venus icon"></i>
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
                            age: {{$item->birth->diffInYears(now())}}
                        </div>
                    </div>

                    @if($item->childrenRecursive()->count() < 2) <div class="action" style="display: flex;
                justify-content: center;">
                        <button class="circular ui button" onclick="$('#add').modal('show'); $('#add_calendar').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                        date: function (date, settings) {
                        if (!date) return ;
                        var day = date.getDate();
                        var month = date.getMonth() + 1;
                        var year = date.getFullYear();
                        return year + '-' + month + '-' + day;
                        }
                    }}); $('#death_calendar').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                        date: function (date, settings) {
                        if (!date) return null;
                        var day = date.getDate();
                        var month = date.getMonth() + 1;
                        var year = date.getFullYear();
                        return year + '-' + month + '-' + day;
                        },
                    }}); $('.selection.dropdown').dropdown();"
                            style="display:flex; justify-self: center; align-self:center; justify-content:center">
                            <i class="icon add"></i> Add Parent
                        </button>

                </div>
                @endif
</div>


<div class="ui modal" id="add">
    <i class="close icon"></i>

    <div class=" content">
        <div class="description">
            <div class="ui header">add parent</div>

        </div>
        <form class="ui form" method="POST" action="{{route('family/parent', $item)}}" autocomplete="off">
            @csrf
            @method('put')



            <div class="field">
                <label>name</label>
                <input type="text" name="name" placeholder=" Name" required>
            </div>
            <div class="ui calendar" id="add_calendar">
                <label>birth</label>
                <div class="ui input left icon">

                    <i class="calendar icon"></i>
                    <input type="text" name="birth" required>
                </div>
            </div>

            <div class="ui calendar" id="death_calendar">
                <label>death</label>
                <div class="ui input left icon">

                    <i class="calendar icon"></i>
                    <input type="text" name="death">
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






</span>
<ul>
    @foreach($item->children as $item)

    @include('family.child', $item)

    @endforeach

</ul>
</li>
</ul>
</div>





@endif

@endforeach

@else

<button class="circular ui icon button" onclick="$('.ui.modal').modal('show'); $('#standard_calendar').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
        date: function (date, settings) {
          if (!date) return '';
          var day = date.getDate();
          var month = date.getMonth() + 1;
          var year = date.getFullYear();
          return year + '-' + month + '-' + day;
        }
      }}); $('.selection.dropdown').dropdown();">
    <i class="icon add"></i> Start Tree
</button>

<div class="ui modal">
    <i class="close icon"></i>

    <div class=" content">
        <div class="description">
            <div class="ui header">add yourself</div>

        </div>
        <form class="ui form" method="POST" action="/family" autocomplete="off">
            @csrf

            <div class="field">
                <label>name</label>
                <input type="text" name="name" placeholder=" Name" required>
            </div>
            <div class="ui calendar" id="standard_calendar">
                <label>birth</label>
                <div class="ui input left icon">


                    <i class="calendar icon"></i>
                    <input type="text" name="date" required>
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



@push('js')

@endpush


@endif
{{-- </div> --}}


@endsection