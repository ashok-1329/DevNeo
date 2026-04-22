<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Auth')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            background-color: #f5f7fb;
        }

        .auth-card {
            max-width: 420px;
            margin: auto;
            margin-top: 100px;
            border-radius: 10px;
        }

        .auth-header {
            background-color: rgb(124 156 63);
            color: #fff;
            padding: 15px;
            border-radius: 10px 10px 0 0;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="card auth-card shadow">

        <div class="auth-header">
            @yield('title', 'Auth')
        </div>

        <div class="card-body">

            {{-- SUCCESS --}}
            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- ERROR --}}
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </div>
    </div>
</div>

</body>
</html>
