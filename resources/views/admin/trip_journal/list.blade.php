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
                                <h5>Trip Journal <span class="float-right"><a href="{{ route('create.trip_journal') }}"
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
                                                        <th scope="col">Title</th>
                                                        {{-- <th scope="col">Text </th> --}}
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $key => $user)
                                                        <tr>
                                                            <th scope="row">{{ $key + 1 }}</th>
                                                            <td>{{ $user->name }}</td>
                                                            {{-- <td>{{ $user->description }}</td> --}}

                                                            <td>
                                                                {{-- <a href="{{ route('show.trip_journal', $user->id) }}"
                                                                    class="btn btn-success">View</a> --}}
                                                                <a href="{{ route('edit.trip_journal', $user->id) }}"
                                                                    class="btn btn-warning">Edit</a>
                                                                {{-- <a class="delete-data btn btn-danger"
                                                                    href="javascript:void(0);"
                                                                    data-url={{ route('delete.trip_journal', $user->id) }}
                                                                    data-title="Are you sure?"
                                                                    data-body="Learn will be deleted!" data-icon="warning"
                                                                    data-success="Learn successfully deleted!"
                                                                    data-cancel="Learn is safe!" title="Delete">Delete</i>
                                                                </a>

                                                                <div style="position: relative;" data-table=""
                                                                    data-id="{{ $user->id }}"
                                                                    data-status="{{ $user->active }}"
                                                                    class="switch delete-data "
                                                                    data-url="{{ route('status.trip_journal', ['id' => $user->id, 'status' => $user->active]) }}"
                                                                    data-title="Are you sure to change status?"
                                                                    data-icon="warning"
                                                                    data-success="Status successfully updated!"
                                                                    data-cancel="Status did not changed!" title="Update">
                                                                    <label>
                                                                        <input class="status" id="togle-{{ $user->id }}"
                                                                            {{ $user->active == '1' ? 'checked' : '' }}
                                                                            type="checkbox">
                                                                        <span class="lever slider round"></span>
                                                                    </label>
                                                                </div> --}}
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
