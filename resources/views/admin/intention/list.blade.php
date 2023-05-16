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
                        <h5>Intentions <span class="float-right"><a href="{{route('create.intentions')}}" class="btn btn-primary">Create</a></span></h5>
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
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach ($mediate_tags as $key => $user)
                                      <tr>
                                        <th scope="row">{{ $key+1 }}</th>
                                        <td>{{$user->name}}</td>
                                        <td>
                                            <a href="{{route('edit.intentions',$user->id)}}" class="btn btn-warning">Edit</a>
                                        </td>
                                      </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                  {{ $mediate_tags->links('pagination::bootstrap-4') }}
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

              <!-- Delete Modal Start -->
  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h3 class="modal-title text-dark" id="exampleModalLongTitle">Delete Modal</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
            {{-- <div class="delete-pic my-2">
                <?xml version="1.0" ?>
                <svg id="Icons" style="width: 120px;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><defs><style>.cls-1{fill:url(#linear-gradient);}.cls-2{fill:#ff7391;}</style><linearGradient gradientUnits="userSpaceOnUse" id="linear-gradient" x1="12" x2="12" y1="0.787" y2="23.088"><stop offset="0" stop-color="#ff4867"/><stop offset="1" stop-color="#e50031"/></linearGradient></defs><circle class="cls-1" cx="12" cy="12" r="11"/><path class="cls-2" d="M13.414,12l3.293-3.293a1,1,0,1,0-1.414-1.414L12,10.586,8.707,7.293A1,1,0,1,0,7.293,8.707L10.586,12,7.293,15.293a1,1,0,1,0,1.414,1.414L12,13.414l3.293,3.293a1,1,0,0,0,1.414-1.414Z"/></svg>
            </div>
          <h4 class="text-dark">Are you sure you want to delete this?</h4>
          <a href="JavaScript:void(0)" id="delete" data-url="sef">Delete</a>
          <button type="button" class="btn btn-danger mt-2" data-dismiss="modal">Delete</button>
          <button type="button" class="btn btn-primary mt-2">Cancel</button> --}}
        </div>
        <div class="modal-footer text-center">
          
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Modal End -->
    
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