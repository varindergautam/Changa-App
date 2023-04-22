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
        <h5>Edit</h5>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                  <hr>
                  <form id="edit-user" action="{{route('update_user')}}" method="post">
                  @isset($user)
                   <input type="" name="id" value={{$user->id}}>
                  @endisset
                    @csrf
                    <div class="row">
                            <div class="col-md-12 col-sm-6 form-group">
                                <label for="input-1">Customer ID</label>
                                <input type="text" class="form-control" id="input-1" value="{{$user->customer_id??""}}" name="customer_id">
                                <span class="error customer_id-error">{{ $errors->first('customer_id') }}</span>
                            </div>
                            <div class="col-md-12 col-sm-6 form-group">
                                <label for="input-1">Created At</label>
                                <input type="text" class="form-control" id="input-1" value="{{$user->created_at??""}}" disabled>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-1">Name</label>
                                <input type="text" class="form-control" id="input-1" value="{{$user->first_name??""}}">
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-2">Email</label>
                                <input type="text" class="form-control" id="input-2" value="{{$user->email??""}}">
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-3">Phone</label>
                                <input type="text" class="form-control" name="phone" id="input-3" value="{{$user->mobile_no??""}}">
                                <span class="error phone-error">{{ $errors->first('phone') }}</span>
                            </div>
                            <div class="col-md-6 col-sm-12 form-group">
                                <label for="input-4">Username</label>
                                <input type="text" class="form-control" id="input-4" value="{{$user->username??""}}">
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-success px-5">Update</button>
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