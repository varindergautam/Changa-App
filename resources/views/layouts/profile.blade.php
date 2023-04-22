@extends('layouts/main')

@section('title', 'Dashboard')


@section('content')
    <div id="wrapper">

        @include('panels/sidebar')
        @include('panels/navbar')

        <div class="content-wrapper">
            <div class="container-fluid">

                <div class="row mt-3">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-pane" id="edit">
                                    <form id="edit-user" action="{{ route('update.profile') }}" method="post" enctype='multipart/form-data'>
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">name</label>
                                            <div class="col-lg-9">
                                                <input class="form-control" type="text"
                                                    value="{{ auth()->user()->first_name }}" name="name">
                                                <span class="error name-error">{{ $errors->first('name') }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                            <div class="col-lg-9">
                                                <input class="form-control" type="email"
                                                    value="{{ auth()->user()->email }}" name="email">
                                                <span class="error email-error">{{ $errors->first('email') }}</span>

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Password</label>
                                            <div class="col-lg-9">
                                                <input class="form-control" type="password" value="" name="password">
                                                <span class="error password-error">{{ $errors->first('password') }}</span>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label">Confirm
                                                password</label>
                                            <div class="col-lg-9">
                                                <input class="form-control" type="password" value=""
                                                    name="confirm_password">
                                                <span
                                                    class="error confirm_password-error">{{ $errors->first('confirm_password') }}</span>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-lg-3 col-form-label form-control-label"></label>
                                            <div class="col-lg-9">
                                                {{-- <input type="reset" class="btn btn-secondary" value="Cancel"> --}}
                                                <input type="submit" class="btn btn-primary" value="Save Changes">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!--start overlay-->
            <div class="overlay toggle-menu"></div>
            <!--end overlay-->

        </div>
        <!-- End container-fluid-->

    </div>
    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->

    </div>

@endsection

@section('scripts')
    <script>
        $('#edit-user').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData($(this)[0]);
            postMultipartAjax($(this).attr('action'), form, '', formHndlError);
        });
    </script>

@endsection
