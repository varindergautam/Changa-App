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
                
                <div class="card-body pt-5">
                    @if ($user->file_type == '0')
                    <audio controls>
                        <source src="{{asset('/storage/file/'. $user->file)}}" type="audio/ogg">
                        <source src="{{asset('/storage/file/'. $user->file)}}" type="audio/mpeg">
                      </audio>
                    @elseif ($user->file_type == '1')
                    <video width="320" height="240" controls>
                        <source src="{{asset('/storage/file/'. $user->file)}}" type="video/mp4">
                        <source src="{{asset('/storage/file/'. $user->file)}}" type="video/ogg">
                        Your browser does not support the video tag.
                      </video>
                    @elseif ($user->file_type == '2')
                    <img alt="image" class="mb-2" src='{{asset('/storage/file/'. $user->file)}}' width="180px"/>
                    @endif
                    
                    <h5 class="card-title">Title : {{$user->title}}</h5>
                    <p class="card-text">{!! $user->description !!}</p>
                    
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