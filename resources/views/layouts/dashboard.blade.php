@extends('layouts/main')

@section('title', 'Dashboard')


@section('content')

    <div id="wrapper">

        @include('panels/sidebar')
        @include('panels/navbar')



        <div class="clearfix"></div>

        <div class="content-wrapper pb-1">
            <div class="container-fluid">

                <!--Start Dashboard Content-->

                <div class="card mt-3">
                    <div class="card-content">
                        <div class="row row-group m-0">
                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('users') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_customer }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Customers</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('mediators') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_mediator }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Mediators</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('mediates') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_mediate }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Mediate</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('learns') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_learn }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Learns</p>
                                    </div>
                                </a>
                            </div>
                            

                            {{-- <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <div class="card-body">
                                    <h5 class="text-white mb-0">8323 <span class="float-right"><i
                                                class="fa fa-usd"></i></span></h5>
                                    <div class="progress my-3" style="height:3px;">
                                        <div class="progress-bar" style="width:55%"></div>
                                    </div>
                                    <p class="mb-0 text-white small-font">Total Revenue <span class="float-right">+1.2%
                                            <i class="zmdi zmdi-long-arrow-up"></i></span></p>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <div class="card-body">
                                    <h5 class="text-white mb-0">6200 <span class="float-right"><i
                                                class="fa fa-eye"></i></span></h5>
                                    <div class="progress my-3" style="height:3px;">
                                        <div class="progress-bar" style="width:55%"></div>
                                    </div>
                                    <p class="mb-0 text-white small-font">Visitors <span class="float-right">+5.2% <i
                                                class="zmdi zmdi-long-arrow-up"></i></span></p>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <div class="card-body">
                                    <h5 class="text-white mb-0">5630 <span class="float-right"><i
                                                class="fa fa-envira"></i></span></h5>
                                    <div class="progress my-3" style="height:3px;">
                                        <div class="progress-bar" style="width:55%"></div>
                                    </div>
                                    <p class="mb-0 text-white small-font">Messages <span class="float-right">+2.2% <i
                                                class="zmdi zmdi-long-arrow-up"></i></span></p>
                                </div>
                            </div> --}}
                        </div>

                        
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-12 col-lg-8 col-xl-8">
                        <div class="card">
                            <div class="card-header">App Traffic
                                <div class="card-action">
                                    <div class="dropdown">
                                        <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret"
                                            data-toggle="dropdown">
                                            <i class="icon-options"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="javascript:void();">Action</a>
                                            <a class="dropdown-item" href="javascript:void();">Another action</a>
                                            <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-circle mr-2 text-white"></i>New Visitor
                                    </li>
                                    <li class="list-inline-item"><i class="fa fa-circle mr-2 text-light"></i>Old Visitor
                                    </li>
                                </ul>
                                <div class="chart-container-1">
                                    <canvas id="chart1"></canvas>
                                </div>
                            </div>

                            <div class="row m-0 row-group text-center border-top border-light-3">
                                <div class="col-12 col-lg-4">
                                    <div class="p-3">
                                        <h5 class="mb-0">45.87M</h5>
                                        <small class="mb-0">Overall Visitor <span> <i class="fa fa-arrow-up"></i>
                                                2.43%</span></small>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="p-3">
                                        <h5 class="mb-0">15:48</h5>
                                        <small class="mb-0">Visitor Duration <span> <i class="fa fa-arrow-up"></i>
                                                12.65%</span></small>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="p-3">
                                        <h5 class="mb-0">245.65</h5>
                                        <small class="mb-0">Pages/Visit <span> <i class="fa fa-arrow-up"></i>
                                                5.62%</span></small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-lg-4 col-xl-4">
                        <div class="card">
                            <div class="card-header">Weekly Bookings
                                <div class="card-action">
                                    <div class="dropdown">
                                        <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret"
                                            data-toggle="dropdown">
                                            <i class="icon-options"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="javascript:void();">Action</a>
                                            <a class="dropdown-item" href="javascript:void();">Another action</a>
                                            <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-container-2">
                                    <canvas id="chart2"></canvas>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <tbody>
                                        <tr>
                                            <td><i class="fa fa-circle text-white mr-2"></i> Direct</td>
                                            <td>992</td>
                                            <td>+25%</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa fa-circle text-light-1 mr-2"></i>Through App</td>
                                            <td>1254</td>
                                            <td>+35%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!--End Row-->


                <!--End Dashboard Content-->

                <!--start overlay-->
                <div class="overlay toggle-menu"></div>
                <!--end overlay-->

            </div>
            <!-- End container-fluid-->

        </div>

        <div class="content-wrapper mt-0 pt-0">
            <div class="container-fluid">

                <!--Start Dashboard Content-->

                <div class="card mt-3">
                    <div class="card-content">
                        <div class="row row-group m-0">
                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('listens') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_listen }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Listen</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('therapy') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_therapy }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Therapy</p>
                                    </div>
                                </a>
                            </div>

                            <div class="col-12 col-lg-6 col-xl-3 border-light">
                                <a href="{{ route('guide') }}">
                                    <div class="card-body">
                                        <h5 class="text-white mb-0">{{ @$total_guide }} </h5>
                                        <div class="progress my-3" style="height:3px;">
                                            {{-- <div class="progress-bar" style="width:55%"></div> --}}
                                        </div>
                                        <p class="mb-0 text-white small-font">Total Guide</p>
                                    </div>
                                </a>
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
        <!--End content-wrapper-->
        <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->


    </div>

@endsection
