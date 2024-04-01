<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style1.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/colors/default.css') }}" id="colorSkinCSS">
    <link rel="stylesheet" href="{{ asset('css/bootstrap1.min.css') }}" />
</head>

<body>
    <div class="main_content_iner ">
        <div class="container-fluid plr_30 body_white_bg pt_30">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="white_box mb_30">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="modal-content cs_modal">
                                    <div class="modal-header">
                                        @if (Session::has('logout_message'))
                                            <div id="logoutMessage" class="alert alert-success">
                                                {{ Session::get('logout_message') }}
                                            </div>
                                        @endif
                                        <h5 class="modal-title">Log in</h5>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('login') }}" method="POST">
                                            {{ csrf_field() }}
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter your email" name="email">
                                            </div>
                                            <div class>
                                                <input type="password" class="form-control" name="password"
                                                    placeholder="Password">
                                            </div>
                                            <button class="btn_1 full_width text-center" type="submit">Log in</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/jquery1-3.4.1.min.js') }}"></script>
<script src="{{ asset('js/popper1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap1.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var logoutMessage = document.getElementById('logoutMessage');
        if (logoutMessage) {
            setTimeout(function() {
                logoutMessage.style.display = 'none';
            }, 3000);
        }
    });
</script>

</html>
