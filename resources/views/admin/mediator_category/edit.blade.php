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
        <h5>@isset($mediator_category)Edit @else Add @endisset</h5>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                  <div class="card-body">
                  <hr>
                  <form id="edit-blog_category" action="{{route('update.mediator_category')}}" method="post">
                  @isset($mediator_category)
                   <input type="hidden" name="id" value={{$mediator_category->id}}>
                  @endisset
                    @csrf
                    <div class="row">
                                                        
                            <div class="col-md-12 col-sm-12 form-group">
                                <label for="input-1">category</label>
                                <input type="text" class="form-control" id="input-1" value="{{$mediator_category->category??""}}" name="category">
                                <span class="error category-error">{{ $errors->first('category') }}</span>
                            </div>
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn btn-success px-5">@isset($mediator_category)Update @else Submit @endisset</button>
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
    $('#edit-blog_category').on('submit', function (e) {
    e.preventDefault();
    var form = new FormData($(this)[0]);
    postMultipartAjax($(this).attr('action'), form, '', formHndlError);
});
</script>
    
@endsection