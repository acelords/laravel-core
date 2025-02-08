<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $site_name ?? env('APP_NAME') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('vendor/acelords/core/css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('vendor/acelords/core/js/app.js') }}" defer></script>
    
    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }
        
        .full-height {
            height: 100vh;
        }
        
        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }
        
        .position-ref {
            position: relative;
        }
        
        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }
        
        .content {
            text-align: center;
        }
        
        .title {
            font-size: 84px;
        }
        
        .title p {
            font-size: 34px;
        }
        
        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        
        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height" style="margin-left: 20px;">
        @if (Route::has('login'))
            <div class="top-right links" style="margin-right: 6em;">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                    
                    <a href="/logout">Logout</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </div>
        @endif
        
        <div class="content mb-4" id="app">
            <div class="title m-b-md">
                <span><a href="{{ url('/') }}">{{ env('APP_NAME') }}</a></span>
                <br/>
                <p>
                    Looks like the theme has not been set!
                    <br/>
    
                    <a href="https://www.acelords.space">AceLords System Engineers</a>
                </p>
            </div>
            
            <div class="links">
                <a href="">Contact Admin</a>
                <a href="">Blog</a>
                <a href="https://acelords.space">Developers</a>
            </div>
    
            <div class="card mt-4">
                <div class="card-header">Login</div>
        
                <div class="card-body">
                    
                    @guest
                        @if($errors->any())
                            <div class="alert alert-danger text-left">
                                <ul>
                                    @foreach($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @include('acelordscore::auth.partials.login-form')
                        
                    @endguest
                    
                    @auth
                        <p>You are already logged in!</p>
                        @if (Route::has('user.redirection'))
                            <a href="{{ route('user.redirection') }}" class="btn btn-primary">
                                Dashboard
                            </a>
                        @endif
                    @endauth
                    
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>
