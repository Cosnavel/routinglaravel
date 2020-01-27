<html>

<head>

</head>

<body>
    <h1>Namensliste</h1>
    <ul>
        @foreach ($names as $name)
        @if ($loop->iteration > 5)
        @break
        @endif
        <li>{{$name}}</li>
        @endforeach
    </ul>

</body>

</html>