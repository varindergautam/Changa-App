@extends('layouts/main')
@section('title', 'Edit User')

@section('content')

    <div id="wrapper">

        @include('panels/sidebar')
        @include('panels/navbar')

        <div class="clearfix"></div>

        <div class="content-wrapper">
            <div class="container-fluid">

                <!--Start Custromer Content-->
                <section id="customer-list">
                    <h5>
                        @isset($user)
                            Edit
                        @else
                            Add
                        @endisset
                    </h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <hr>
                                    <form id="edit-user" action="{{ route('update.trip_journal') }}" method="post"
                                        enctype='multipart/form-data'>
                                        @isset($user)
                                            <input type="hidden" name="id" value={{ @$user->id }}>
                                        @endisset
                                        @csrf
                                        <div class="row">
                                            
                                            <div class="col-md-12 col-sm-12 form-group">
                                                <label for="input-1">Title</label>
                                                <input type="text" class="form-control" id="input-1"
                                                    value="{{ @$user->name ?? '' }}" name="title">
                                                <span class="error title-error">{{ $errors->first('title') }}</span>
                                            </div>

                                            <div class="col-md-12 col-sm-12 form-group">
                                                <label for="text_1">Description 1</label>
                                                <textarea class="form-control" id="text_1"  name="text_1">{{ @$user->text_1 }}</textarea>
                                                <span
                                                    class="error text_1-error">{{ $errors->first('text_1') }}</span>
                                            </div>

                                            <div class="col-md-12 col-sm-12 form-group">
                                                <label for="text_2">Description 2</label>
                                                <textarea class="form-control" id="text_2"  name="text_2">{{ @$user->text_2 }}</textarea>
                                                <span
                                                    class="error text_2-error">{{ $errors->first('text_2') }}</span>
                                            </div>

                                            <div class="col-md-12 form-group">
                                                <button type="submit" class="btn btn-success px-5">
                                                    @isset($user)
                                                        Update
                                                    @else
                                                        Submit
                                                    @endisset
                                                </button>
                                                <a href="#"><button type="button"
                                                        class="btn btn-danger px-5">Cancel</button></a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--End Custromer Content-->

                <!--start overlay-->
                <div class="overlay toggle-menu"></div>
                <!--end overlay-->

            </div>
            <!-- End container-fluid-->

        </div>
        <!--End content-wrapper-->
        <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->




    </div>
@endsection

@section('scripts')
    <script>
        // CKEDITOR.replace('description');

        $('#edit-user').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData($(this)[0]);
            postMultipartAjax($(this).attr('action'), form, '', formHndlError);
        });
    </script>

@endsection
