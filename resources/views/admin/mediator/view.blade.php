@extends('layouts/main')
@section('title','View User')

@section('content')

<div id="wrapper">

    @include('panels/sidebar')
    @include('panels/navbar')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">


        <div class="row mt-3">
            <div class="col-lg-12">
               <div class="card profile-card-2">
                {{-- <div class="card-img-block">
                    <img class="img-fluid" src="https://via.placeholder.com/800x500" alt="Card image cap">
                </div> --}}
                <div class="card-body pt-3">
                    <img src="{{asset('/storage/profile_pic/'. $user->profile_pic)}}" alt="profile-image" class="profile">
                    <h5 class="card-title">{{$user->customer_id}}</h5>
                    <p class="card-text">{{$user->first_name}}</p>
                    <p class="card-text">{{$user->username}}</p>
                    <p class="card-text">{{$user->email}}</p>
                    <p class="card-text">{{$user->phone}}</p>
                    <div class="icon-block">
                      {{-- <a href="javascript:void();"><i class="fa fa-facebook bg-facebook text-white"></i></a>
                      <a href="javascript:void();"> <i class="fa fa-twitter bg-twitter text-white"></i></a>
                      <a href="javascript:void();"> <i class="fa fa-google-plus bg-google-plus text-white"></i></a> --}}
                    </div>
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
       </div><!--End content-wrapper-->
       <!--Start Back To Top Button-->
        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
        <!--End Back To Top Button-->
 
        
</div>

    
@endsection