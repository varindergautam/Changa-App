@extends('layouts/main')

@section('title', 'Dashboard')


@section('content')

    <body class="bg-theme bg-theme1">
        <div id="wrapper">

            @include('panels/sidebar')
            @include('panels/navbar')


            <div class="content-wrapper">
                <div class="container-fluid">

                    <!--Start Custromer Content-->
                    <section id="customer-list">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <h5>Therapy <span class="float-right"><a href="{{ route('create.therapy') }}"
                                            class="btn btn-primary">Create</a></span></h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Sr. No.</th>
                                                        <th scope="col">Tag</th>
                                                        <th scope="col">Title</th>
                                                        {{-- <th scope="col">Description</th> --}}
                                                        <th scope="col">File</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $key => $user)
                                                        <tr>
                                                            <th scope="row">{{ $key + 1 }}</th>
                                                            <td>{{ $user->therapy_tag_id }}</td>
                                                            <td>{{ $user->title }}</td>
                                                            {{-- <td>{{ $user->description }}</td> --}}
                                                            <td>
                                                                @if ($user->file_type == '0')
                                                                    <audio controls>
                                                                        <source
                                                                            src="{{ asset('/storage/file/' . $user->file) }}"
                                                                            type="audio/ogg">
                                                                        <source
                                                                            src="{{ asset('/storage/file/' . $user->file) }}"
                                                                            type="audio/mpeg">
                                                                    </audio>
                                                                @elseif ($user->file_type == '1')
                                                                    <video width="180" height="140" controls>
                                                                        <source
                                                                            src="{{ asset('/storage/file/' . $user->file) }}"
                                                                            type="video/mp4">
                                                                        <source
                                                                            src="{{ asset('/storage/file/' . $user->file) }}"
                                                                            type="video/ogg">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                @elseif ($user->file_type == '2')
                                                                    <img alt="image" class="mb-2"
                                                                        src='{{ asset('/storage/file/' . $user->file) }}'
                                                                        width="100px" />
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('show.therapy', $user->id) }}"
                                                                    class="btn btn-success">View</a>
                                                                <a href="{{ route('edit.therapy', $user->id) }}"
                                                                    class="btn btn-warning">Edit</a>
                                                                <a class="delete-data btn btn-danger"
                                                                    href="javascript:void(0);"
                                                                    data-url={{ route('delete.therapy', $user->id) }}
                                                                    data-title="Are you sure?"
                                                                    data-body="Therapy will be deleted!" data-icon="warning"
                                                                    data-success="Therapy successfully deleted!"
                                                                    data-cancel="Learn is safe!" title="Delete">Delete</i>
                                                                </a>

                                                                <div style="position: relative;" data-table=""
                                                                    data-id="{{ $user->id }}"
                                                                    data-status="{{ $user->active }}"
                                                                    class="switch delete-data "
                                                                    data-url="{{ route('status.therapy', ['id' => $user->id, 'status' => $user->active]) }}"
                                                                    data-title="Are you sure to change status?"
                                                                    data-icon="warning"
                                                                    data-success="Status successfully updated!"
                                                                    data-cancel="Status did not changed!" title="Update">
                                                                    <label>
                                                                        <input class="status"
                                                                            id="togle-{{ $user->id }}"
                                                                            {{ $user->active == '1' ? 'checked' : '' }}
                                                                            type="checkbox">
                                                                        <span class="lever slider round"></span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
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

            <!--Start Back To Top Button-->
            <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
            <!--End Back To Top Button-->

            <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header text-center">
                            <h3 class="modal-title text-dark" id="exampleModalLongTitle">Delete Modal</h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="delete-pic my-2">
                                <?xml version="1.0" ?>
                                <svg id="Icons" style="width: 120px;" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <style>
                                            .cls-1 {
                                                fill: url(#linear-gradient);
                                            }

                                            .cls-2 {
                                                fill: #ff7391;
                                            }
                                        </style>
                                        <linearGradient gradientUnits="userSpaceOnUse" id="linear-gradient" x1="12"
                                            x2="12" y1="0.787" y2="23.088">
                                            <stop offset="0" stop-color="#ff4867" />
                                            <stop offset="1" stop-color="#e50031" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="cls-1" cx="12" cy="12" r="11" />
                                    <path class="cls-2"
                                        d="M13.414,12l3.293-3.293a1,1,0,1,0-1.414-1.414L12,10.586,8.707,7.293A1,1,0,1,0,7.293,8.707L10.586,12,7.293,15.293a1,1,0,1,0,1.414,1.414L12,13.414l3.293,3.293a1,1,0,0,0,1.414-1.414Z" />
                                </svg>
                            </div>
                            <h4 class="text-dark">Are you sure you want to delete this?</h4>
                            <a href="JavaScript:void(0)" id="delete" data-url="sef">Delete</a>
                            <button type="button" class="btn btn-danger mt-2" data-dismiss="modal">Delete</button>
                            <button type="button" class="btn btn-primary mt-2">Cancel</button>
                        </div>
                        <div class="modal-footer text-center">

                        </div>
                    </div>
                </div>
            </div>

        </div>


    @endsection

    @section('scripts')
        <script>
            $(document).on('click', '.delete', function(e) {
                let url = $(this).data('url');
                console.log(url);
                $('#commonModal').modal('show');
                getAjax(url, respons234e);
            });

            function respons234e(response) {
                console.log(response);
                // $('.modal-body').html('response');
            }
        </script>
    @endsection
