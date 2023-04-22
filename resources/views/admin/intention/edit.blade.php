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
        <h5>@isset($mediate_tag)Edit @else Add @endisset</h5>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                  <hr>
                  <form id="edit-mediate_tags" action="{{route('update.intentions')}}" method="post">
                  @isset($mediate_tag)
                   <input type="hidden" name="id" value={{$mediate_tag->id}}>
                  @endisset
                    @csrf
                    <div class="row">
                                                        
                            <div class="col-md-12 col-sm-12 form-group">
                                <label for="input-1">Name</label>
                                <input type="text" class="form-control" id="input-1" value="{{$mediate_tag->name??""}}" name="name">
                                <span class="error name-error">{{ $errors->first('name') }}</span>
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-success px-5">@isset($mediate_tag)Update @else Submit @endisset</button>
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
    $('#edit-mediate_tags').on('submit', function (e) {
    e.preventDefault();
    var form = new FormData($(this)[0]);
    postMultipartAjax($(this).attr('action'), form, '', formHndlError);
});
</script>
    
@endsection