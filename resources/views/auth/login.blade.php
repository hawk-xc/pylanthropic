<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta http-equiv="Content-Language" content="en" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login - Dashboard Admin Bantubersama</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex, nofollow">
        <meta name="googlebot-news" content="noindex, nofollow">

        <meta name="msapplication-tap-highlight" content="no" />
        <link href="{{ asset('admin/main.css') }}" rel="stylesheet" />
    </head>

    <body>
        <div class="app-container app-theme-white body-tabs-shadow">
            <div class="app-container">
                <div class="h-100 bg-plum-plate bg-animation">
                    <div class="d-flex h-100 justify-content-center align-items-center">
                        <div class="mx-auto app-login-box col-md-8">
                            <div class="app-logo-inverse mx-auto mb-3"></div>
                            <div class="modal-dialog w-100 mx-auto">
                                <div class="modal-content">
                                    <form action="{{ route('adm.login.submit') }}" method="post">
                                    @csrf
                                        <div class="modal-body">
                                            <div class="h5 modal-title text-center">
                                                <h4 class="mt-2">
                                                    <div>Welcome back,</div>
                                                    <span>Please sign in to your account below.</span>
                                                </h4>
                                            </div>
                                            @if(Session::get('message'))
                                                <div class="alert alert-{{ Session::get('status') ?? 'warning' }} alert-dismissible fade show" role="alert">
                                                    <span>{{ Session::get('message') }}</span>
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endif
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <input name="email" id="exampleEmail" placeholder="Email here..." type="email" class="form-control" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <input name="password" id="examplePassword" placeholder="Password here..." type="password" class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="position-relative form-check">
                                                <input name="check" id="exampleCheck" type="checkbox" class="form-check-input" />
                                                <label for="exampleCheck" class="form-check-label">Keep me logged in</label>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer clearfix">
                                            <div class="float-left">
                                                <a href="javascript:void(0);" class="btn-lg btn btn-link">Recover Password</a>
                                            </div>
                                            <div class="float-right">
                                                <button type="submit" class="btn btn-primary btn-lg">Login to Dashboard</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('admin/scripts/main.js') }}"></script>
    </body>
</html>
