<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ SITE_NAME}} | Log in</title>
  <link rel="shortcut icon" href="{{ $favicon }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{URL::to('/')}}/public/backend/bootstrap/css/bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{URL::to('/')}}/public/backend/dist/css/AdminLTE.min.css">
</head>
<body class="hold-transition">

<div class="flash-container" class="ml-15">
  @if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
      <a href="#"  class="alert-close pull -right" data-dismiss="alert">&times;</a>
    {{ Session::get('message') }}
    </div>
  @endif
</div>

<div class="admin-login-page">
    <div class="container-fluid">
        <div class="row">
            <div class="login-form-sec">
                <div class="admin-login-bg" style="background:url(<?php echo $admin_login_img; ?>); background-repeat: no-repeat; background-size: contain; background-position: 50% 50%;"></div>
                <div class="admin-login-content">
                    <div class="admin-login-form">
                        <div class="text-center">
                            <a href="{{ url('/') }}"><img src="{{ $logo ?? '' }}" alt="logo"></a>
                        </div>
                        <h2 class="text-center welcome-txt">Welcome Admin !!!</h2>
                        <form action="{{ url('admin/authenticate') }}" method="post" id="admin_login">
                            {{ csrf_field() }}
                            <div class="form-group has-feedback">
                                <label for="username">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="row">
                                <div class="col-xs-8">
                                    <div class="checkbox icheck">
                                        <label>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</body>
</html>

