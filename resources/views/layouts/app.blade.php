<html>

<head>
    <title>Family Tree</title>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.7.8/semantic.min.css"
        integrity="sha256-pquaucmYjfUqK251HC4uCXIKb2TQ4brXeUN2STYrJeg=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.7.8/semantic.min.js"></script>

    <link rel="stylesheet" href="/css/tree.css">
</head>

<body>
    @include('layouts.header')

    <div>
        @yield('content')
    </div>

</body>



@stack('js')



</html>