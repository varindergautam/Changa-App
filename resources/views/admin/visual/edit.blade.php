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
                                    <form id="edit-user" action="{{ route('update.visual') }}" method="post"
                                        enctype='multipart/form-data'>
                                        @isset($user)
                                            <input type="hidden" name="id" value={{ @$user->id }}>
                                        @endisset
                                        @csrf
                                        <div class="row">
                                            
                                            <div class="col-md-12 col-sm-12 form-group">
                                                <label for="input-1">Title</label>
                                                <input type="text" class="form-control" id="input-1"
                                                    value="{{ @$user->name ?? '' }}" name="name">
                                                <span class="error title-error">{{ $errors->first('name') }}</span>
                                            </div>

                                            <div class="col-md-12 col-sm-12 form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" name="description">{{ @$user->description }}</textarea>
                                                <span
                                                    class="error description-error">{{ $errors->first('description') }}</span>
                                            </div>

                                            <div class="col-md-12 col-sm-12 form-group">
                                                <label for="input-2">Upload</label>
                                                <input type="file" class="form-control" id="input-2" name="file">
                                                @isset($user)
                                                    <input type="hidden" value="{{ $user->file_type ?? '' }}" name="file_type">
                                                    <input type="hidden" value="{{ $user->file ?? '' }}" name="check_file">
                                                    @if ($user->file_type == '0')
                                                        <audio controls>
                                                            <source src="{{ asset('/storage/file/' . $user->file) }}"
                                                                type="audio/ogg">
                                                            <source src="{{ asset('/storage/file/' . $user->file) }}"
                                                                type="audio/mpeg">
                                                        </audio>
                                                    @elseif ($user->file_type == '1')
                                                        <video width="320" height="240" controls>
                                                            <source src="{{ asset('/storage/file/' . $user->file) }}"
                                                                type="video/mp4">
                                                            <source src="{{ asset('/storage/file/' . $user->file) }}"
                                                                type="video/ogg">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @elseif ($user->file_type == '2')
                                                        <img alt="image" class="mb-2"
                                                            src='{{ asset('/storage/file/' . $user->file) }}' width="100px" />
                                                    @endif

                                                @endisset
                                                <span class="error file-error">{{ $errors->first('file') }}</span>
                                            </div>

                                            {{-- <div class="col-md-12 col-sm-12 form-group">
                                                <label for="input-1">Background Image</label>
                                                <input type="hidden" value="{{ @$user->background_image }}" name="background_image_hiden">
                                                <input type="file" class="form-control" id="background_image" name="background_image">
                                                <span
                                                    class="error background_image-error">{{ $errors->first('background_image') }}</span>
                                                    @if (@$user->background_image)
                                                        <img alt="image" class="mb-2"
                                                            src='{{ asset('/storage/file/' . $user->background_image) }}' width="100px" />
                                                    @endif
                                            </div> --}}


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
        $('#edit-user').on('submit', function(e) {
            e.preventDefault();
            var form = new FormData($(this)[0]);
            postMultipartAjax($(this).attr('action'), form, '', formHndlError);
        });
    </script>

@endsection
