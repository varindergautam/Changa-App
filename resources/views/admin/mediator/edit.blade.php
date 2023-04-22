@extends('layouts/main')
@section('title','Edit User')

@section('content')

<div id="wrapper">

    @include('panels/sidebar')
    @include('panels/navbar')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

    <!--Start Custromer Content-->
    <section id="customer-list">
        <h5>@isset($user)Edit @else Add @endisset</h5>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                  <hr>
                  <form id="edit-user" action="{{route('update.mediators')}}" method="post" enctype='multipart/form-data'>
                  @isset($user)
                   <input type="hidden" name="id" value={{$user->id}}>
                  @endisset
                    @csrf
                    <div class="row">
                            <div class="col-md-12 col-sm-6 form-group">
                                <label for="input-1">Customer ID</label>
                                <input type="text" class="form-control" id="input-1" value="{{$user->customer_id??'CHA-'.random_int(10000, 99999)}}" name="customer_id" readonly>
                                <span class="error customer_id-error">{{ $errors->first('customer_id') }}</span>
                            </div>
                            @isset($user)
                            <div class="col-md-12 col-sm-6 form-group">
                                <label for="input-1">Created At</label>
                                <input type="text" class="form-control" id="input-1" value="{{$user->created_at??""}}" disabled>
                            </div> 
                            @endisset
                            
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-1">Name</label>
                                <input type="text" class="form-control" id="input-1" value="{{$user->first_name??""}}" name="name">
                                <span class="error name-error">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-2">Email</label>
                                <input type="text" class="form-control" id="input-2" value="{{$user->email??""}}" name="email">
                                <span class="error email-error">{{ $errors->first('email') }}</span>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-3">Phone</label>
                                <input type="text" class="form-control" name="phone" id="input-3" value="{{$user->phone ??""}}">
                                <span class="error phone-error">{{ $errors->first('phone') }}</span>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-4">Username</label>
                                <input type="text" class="form-control" id="input-4" value="{{$user->username??""}}" name="username">
                                <span class="error username-error">{{ $errors->first('username') }}</span>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-2">Mediator Category</label>
                                <select class="form-control" name="category">
                                    <option disabled selected>Select Category</option>
                                    @if ($mediator_categories->count() > 0)
                                        @foreach ($mediator_categories as $mediator_category)
                                            <option value="{{$mediator_category->id}}" {{$mediator_category->id == @$user->mediator_category_id ? 'selected' : ''}}>{{ $mediator_category->category }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="error category-error">{{ $errors->first('category') }}</span>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-4">Profile Pic</label>
                                <input type="file" class="form-control" id="input-4" value="{{@$user->profile_pic??""}}" name="profile_pic">
                                @isset($user->profile_pic)
                                    <img src="{{asset('/storage/file/'. $user->profile_pic)}}" width="100px">
                                @endisset
                                <span class="error profile_pic-error">{{ $errors->first('profile_pic') }}</span>
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-success px-5">@isset($user)Update @else Submit @endisset</button>
                                <a href="#"><button type="button" class="btn btn-danger px-5">Cancel</button></a> 
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

</div><!--End content-wrapper-->
<!--Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
<!--End Back To Top Button-->




</div>
@endsection

@section('scripts')
<script>
    $('#edit-user').on('submit', function (e) {
    e.preventDefault();
    var form = new FormData($(this)[0]);
    postMultipartAjax($(this).attr('action'), form, '', formHndlError);
});
</script>
    
@endsection