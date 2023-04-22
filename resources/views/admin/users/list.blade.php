@extends('layouts/main')

@section('title', 'Dashboard')

@section('content')

    <style>
        
    </style>

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
                                <h5>Customer Listing <span class="float-right"><a href="{{ route('create.user') }}"
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
                                                        <th scope="col">Customer ID</th>
                                                        <th scope="col">Created Date</th>
                                                        <th scope="col">Customer Name</th>
                                                        <th scope="col">Email Address</th>
                                                        <th scope="col">Phone Number</th>
                                                        <th scope="col">User Name</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        <tr>
                                                            <th scope="row">1</th>
                                                            <td>{{ $user->customer_id }}</td>
                                                            <td>{{ $user->created_at }}</td>
                                                            <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->phone }}</td>
                                                            <td>{{ $user->username }}</td>
                                                            <td>
                                                                <a href="{{ route('show.user', $user->id) }}"
                                                                    class="btn btn-success">View</a>
                                                                <a href="{{ route('edit.user', $user->id) }}"
                                                                    class="btn btn-warning">Edit</a>
                                                                {{-- <a href="JavaScript:void(0)"
                                                                    class="btn btn-danger openModal" type="button"
                                                                    data-toggle="modal" data-target="#exampleModalCenter"
                                                                    data-url="{{ route('modal.user', $user->id) }}">Delete</a> --}}

                                                                <a class="delete-data btn btn-danger"
                                                                    href="javascript:void(0);"
                                                                    data-url={{ route('delete.user', $user->id) }}
                                                                    data-title="Are you sure?"
                                                                    data-body="Customer will be deleted!"
                                                                    data-icon="warning"
                                                                    data-success="Customer successfully deleted!"
                                                                    data-cancel="Customer is safe!"
                                                                    title="Delete">Delete</i>
                                                                </a>

                                                                <div style="position: relative;" data-table=""
                                                                    data-id="{{ $user->id }}"
                                                                    data-status="{{ $user->active }}"
                                                                    class="switch delete-data "
                                                                    data-url="{{ route('status.user', ['id' => $user->id, 'status' => $user->active]) }}"
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

            {{-- @include('modals/delete_users')     --}}

        </div>


    @endsection

    @section('scripts')

    @endsection
