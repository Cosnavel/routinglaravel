# routinglaravel

repo for laravel course

1.  Model erstellen

```php
php artisan make:model Family -crm
```

2.  Migration befüllen

```php
Schema::create('families', function (Blueprint $table) {
    $table->bigIncrements('id');
    $table->string('name');
    $table->date('birth');
    $table->date('death')->nullable();
    $table->string('gender');
    $table->integer('parent')->nullable();
    $table->timestamps();
});
```

3.  Migrieren – beachte, dass du dich in der Vagrant Box befindest.
4.  Model ausfüllen – Mass Assignment, Datumsspalten

```php
protected $fillable = ['name', 'birth', 'death', 'gender', 'parent'];

protected $dates = ['birth', 'death'];
```

5. Beziehungen definieren

```php
public function child()
{
    return $this->hasMany('App\Family', 'parent')->limit(2);
}
public function childRecursive()
{
    return $this->child()->with('childRecursive');
}
```

6.  Request erstellen und Validierung hinzufügen

```php
public function authorize()
{
    return true;
}

/**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
public function rules()
{
    return [
        //
        'name' => 'required',
        'birth' => 'required',
        'death' => 'nullable|sometimes|after:birth',
        'gender' => 'required'
    ];
}
```

7.  Controller und Methoden hinzufügen

```php
namespace App\Http\Controllers;

use App\Family;
use Illuminate\Http\Request;
use App\Http\Requests\FamilyRequest;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tree = Family::with('childRecursive')->get();

        return view('tree.index', compact('tree'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FamilyRequest $request)
    {
        Family::create($request->all());

        return redirect('/')->withStatus(__('erfolgreich hinzugefügt'));
    }

    public function parent(FamilyRequest $request, Family $item)
    {
        $item->child()->create($request->all());

        return redirect('/')->withStatus(__('erfolgreich hinzugefügt'));
    }
}
```

8.  Routes definieren

```php
Route::put('/parent/{item}', 'FamilyController@parent')->name('parent');
Route::resource('/', 'FamilyController');
```

9.  CSS-Dateien im App Layout einfügen – _tree.css_ zuvor in den Ordner _public/css/_ kopieren

```html
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"
></script>

<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.7.8/semantic.min.css"
  integrity="sha256-pquaucmYjfUqK251HC4uCXIKb2TQ4brXeUN2STYrJeg="
  crossorigin="anonymous"
/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.7.8/semantic.min.js"></script>

<link rel="stylesheet" href="/css/tree.css" />
```

10. Views schreiben – du kannst auch eigene Views für die Formulare erstellen anstatt von Modals

```php
//index
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

@if ($tree->isNotEmpty())

@foreach($tree as $item)

@if($item->parent == null)

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
                    @if($item->childRecursive()->count() < 2) <div class="action"
                        style="display: flex; justify-content:center">
                        <button class="circular ui button" onclick="$('#addparent').modal('show'); $('#addparent_calendar').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                            date: function(date,settings) {
        if(!date) return '';
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        return year + '-' + month + '-' + day;
    }
                        }}); $('#death_calendar').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                            date: function(date,settings) {
        if(!date) return '';
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        return year + '-' + month + '-' + day;
    }
                        }}); $('.selection.dropdown').dropdown();">
                            add parent
                        </button>
                </div>
                @endif

            </div>
</div>


<div class="ui modal" id="addparent">
    <i class="close icon"></i>

    <div class="content">
        <div class="description">
            <div class="ui header">add parent</div>
        </div>
        <form class="ui form" method="POST" action="{{route('parent', $item)}}" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="field">
                <label>Name</label>
                <input type="text" name="name" placeholder="name" required>
            </div>
            <div class="ui calendar" id="addparent_calendar">
                <label>birth</label>
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="Date/Time" name="birth" required>
                </div>
            </div>

            <div class="ui calendar" id="death_calendar">
                <label>death</label>
                <div class="ui input left icon">
                    <i class="calendar icon"></i>
                    <input type="text" placeholder="Date/Time" name="death">
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
<ul>
    @foreach($item->child as $item)
    @include('tree.child', $item)
    @endforeach
</ul>
</li>
</ul>
</div>
@endif
@endforeach
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
```

```php
//child.blade.php
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
                    <i class="hospital icon"></i>
                    <span class="date">death: @isset($item->death) {{$item->death->format('d.m.Y')}} @endisset</span>
                </div>
                <div class="description">
                    <span class="date">current age: @if(isset($item->death))
                        {{$item->birth->diffInYears($item->death)}}
                        @else {{$item->birth->diffInYears(now())}} @endif
                    </span>
                </div>
            </div>
            @if($item->childRecursive()->count() < 2) <div class="action" style="display: flex; justify-content:center">
                <button class="circular ui button" onclick="$('{{'#addparent'.$item->id}}').modal('show'); $('{{'#addparent_calendar'.$item->id}}').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                            date: function(date,settings) {
        if(!date) return '';
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        return year + '-' + month + '-' + day;
    }
                        }}); $('{{'#death_calendar'.$item->id}}').calendar({ type: 'date', firstDayOfWeek: 1, formatter: {
                            date: function(date,settings) {
        if(!date) return '';
        var day = date.getDate();
        var month = date.getMonth();
        var year = date.getFullYear();
        return year + '-' + month + '-' + day;
    }
                        }}); $('{{'#selection_child'.$item->id}}').dropdown();">
                    add parent
                </button>
        </div>
        @endif
    </div>
    </div>
    <div class="ui modal" id="{{'addparent'.$item->id}}">
        <i class="close icon"></i>
        <div class="content">
            <div class="description">
                <div class="ui header">add parent</div>
            </div>
            <form class="ui form" method="POST" action="{{route('parent', $item)}}" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="field">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="name" required>
                </div>
                <div class="ui calendar" id="{{'addparent_calendar'.$item->id}}">
                    <label>birth</label>
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Date/Time" name="birth" required>
                    </div>
                </div>
                <div class="ui calendar" id="{{'death_calendar'.$item->id}}">
                    <label>death</label>
                    <div class="ui input left icon">
                        <i class="calendar icon"></i>
                        <input type="text" placeholder="Date/Time" name="death">
                    </div>
                </div>
                <div class="field">
                    <label>gender</label>
                    <div class="ui selection dropdown" id="{{'selection_child'.$item->id}}">
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
    <ul>
        @foreach($item->child as $item)
        @include('tree.child', $item)
        @endforeach
    </ul>
</li>
```
