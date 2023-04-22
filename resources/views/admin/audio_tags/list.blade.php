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
                        <h5>Tags <span class="float-right"><a href="{{route('create.audio_tags')}}" class="btn btn-primary">Create</a></span></h5>
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
                                        <th scope="col">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($mediate_tags as $key => $user)
                                      <tr>
                                        <th scope="row">{{ $key+1 }}</th>
                                        <td>{{$user->tag}}</td>
                                        <td>
                                            <a href="{{route('edit.audio_tags',$user->id)}}" class="btn btn-warning">Edit</a>
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
        <script>
          $(document).on('click', '.openModal', function (e) {
          e.preventDefault();
          var url = $(this).data('url');
          $.ajax({
              url: url,
              type: 'GET',
              dataType: 'html'
          })
              .done(function (data) {
                  $('#exampleModalCenter').modal('show');
                  $('.modal-body').html(data);

              })
              .fail(function () {
                  alert('Something went wrong, Please try again...');
              });
          
      });

      $(document).on('click', '.ok', function(e) {
      // $('.delete').on('submit', function (e) {
        $(this).parent("form").submit();
        e.preventDefault();
        // let url = $(this).data('url');
        // console.log(url);
        var form = new FormData($(this)[0]);
        postMultipartAjax($(this).attr('action'), form, '', formHndlError);
    });
        </script>
    @endsection