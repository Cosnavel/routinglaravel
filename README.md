<p align="center"><a href="https://www.webmasters-fernakademie.de"><img src="https://www.webmasters-fernakademie.de/images/wfa_img/logo-wfa.png?1571290125" width="400"></a></p>
<p align="center">
<a href="https://www.codefactor.io/repository/github/cosnavel/routinglaravel"><img src="https://www.codefactor.io/repository/github/cosnavel/routinglaravel/badge" alt="CodeFactor" /></a>
    <a href="https://github.styleci.io/repos/236512615"><img src="https://github.styleci.io/repos/236512615/shield?branch=master" alt="StyleCI"></a>
</p>
<p align="center">
Übung Familienstammbaum - Class "Einstieg in Laravel 7"
</p>

## About the familytree
Als große Übung der Class "Einstieg in Laravel 7" hast du einen Familienstammbaum mit Laravel umgesetzt. In diesem Verzeichnis findest du den gesamten Code meiner Musterlösung. Ein Stammbaum stellt eine Schwierigkeit dar, da der Stammbaum unendlich weit verzweigt werden kann. Wir wollen aber nicht für jede Generation eine neue Tabelle nutzen. Aus diesem Grund verwenden wir eine einzige Tabelle. Das Stichwort, um den Stammbaum umzusetzen, lautet Rekursion. 

Die Basis des Stammbaums bist du. Danach kommen deine Eltern und danach deren Eltern – deine Großeltern. Ich beschränke meine Musterlösung nur auf die Eltern, nicht auf irgendwelche Geschwister, Onkel, Tanten etc. Diese kannst du bei Bedarf gerne selbst hinzufügen. Für jede Person im Stammbaum gibt es weitere Vorgaben. Es muss ein Name, Geburtsdatum und Geschlecht angegeben werden. Optional soll das Todesdatum angegeben werden. Es soll das derzeitige Alter der Person, also die Differenz aus dem heutigen Datum und dem Geburtsdatum errechnet werden. Ist die Person bereits verstorben, soll das erreichte Alter aus dem Geburts und Todesdatum errechnet werden. 

Ich verwende in meiner Lösung Formantic UI und ein bisschen CSS, um die Elemente in einem Baum anzuordnen (*public/css/tree.css*). Du kannst deinen Familienstammbaum natürlich gerne verschönern. Teile mir doch ein Bild von deinem Stammbaum! Die Möglichkeit zum Teilen von Bildern wirst du in der Class "Laravel 7 für Fortgeschrittene" erarbeiten ;) 

## Guide

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
---
### N + 1 Probleme und Code Style fixen

11. <a href="https://github.com/barryvdh/laravel-debugbar">Laravel Debugbar</a> installieren `composer require barryvdh/laravel-debugbar --dev`

12. Im Model den Child Count als Scope hinzufügen und in der *child* Beziehung die Begrenzung der Resultate entfernen

```php
 public function child()
{
    return $this->hasMany('App\Family', 'parent');
}

public function childRecursive()
{
    return $this->child()->with('childRecursive')->withChildCount();
}

public function scopeWithChildCount($query)
{
    return $query->withCount('child');
}
```

13. In der *index* Action des Controllers die Query bearbeiten. Das Scope Child Count wird hinzugefügt und anstatt einer Collection nur das Element ausgegeben bei dem `parent = null` ist. 
```php
$item = Family::whereNull('parent')->withChildCount()->with('childRecursive')->first();

return view('tree.index', compact('item'));
```

14. Das Modal zum Hinzufügen eines Elternteils in eine anonyme Komponente auslagern
```php
//resources/views/components/add_parent_modal.blade.php
@props(['item'])

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

```
15. Den Button zum Öffnen des Modals in eine anonyme Komponente auslagern
```php
//resource/views/components/open_modal_button.blade.php
@props(['item'])

<div class="action" style="display: flex; justify-content:center">
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
```

16. *index.blade.php* entsprechend der Veränderugnen anpassen und den *foreach* Loop zum Einbinden der *child.blade.php* durch einen *each loop ersetzen. Das Modal und den Button durch die anonymen Komponenten ersetzen.
```php
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
```

17. *child.blade.php* entsprechend der Veränderugnen anpassen und den *foreach* Loop zum Einbinden der *child.blade.php* durch einen *each* Loop ersetzen. Das Modal und den Button durch die anonymen Komponenten ersetzen.

```php
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
            @if($item->child_count
            < 2) <x-open_modal_button :item="$item" />
            @endif
        </div>
    </div>

    <x-add_parent_modal :item="$item" />

    <ul>
        @each('tree.child', $item->childRecursive, 'item')
    </ul>

</li>
```

