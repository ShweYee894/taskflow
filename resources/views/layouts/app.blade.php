<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TaskFlow')</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .task-card{
            border-radius: 12px;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }

        .task-card:hover{
           box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08) !important;
           transform: translate(-2px);
        }


    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{route('home')}}">
                TaskFlow
            </a>
        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>
</body>
</html>