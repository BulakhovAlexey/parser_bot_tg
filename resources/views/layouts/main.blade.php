<!doctype html>
<html lang=ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TG-Bot</title>
    @vite('resources/css/app.css')
</head>
<body class="flex min-h-screen flex-col">

@include('common.header')

<main class="flex-1 max-w-[1200px] mx-auto my-0 w-full py-5">
    @yield('content')
</main>

{{--@include('common.footer')--}}

</body>
</html>
