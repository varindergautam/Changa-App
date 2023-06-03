@extends('layouts.main')

@section('title', 'Terms and Policy')

@section('custom-css')
    <style>
        .left-side-bar ul li a i {
            margin-top: 0px;
        }
    </style>
@endsection

@section('content')


    <div id="wrapper">

        @include('panels/sidebar')
        @include('panels/navbar')

        <div class="clearfix"></div>

        <div class="content-wrapper">
            <div class="container-fluid">

                <!--Start Custromer Content-->
                <section id="customer-list">
                    <h5>Terms and Policy</h5>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <hr>
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="terms_conditions-tab" data-toggle="tab"
                                                href="#terms_conditions" role="tab" aria-controls="terms_conditions"
                                                aria-selected="true">Terms & Conditions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="privacy_policy-tab" data-toggle="tab"
                                                href="#privacy_policy" role="tab" aria-controls="privacy_policy"
                                                aria-selected="false">Privacy Policy</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="terms_conditions" role="tabpanel"
                                            aria-labelledby="terms_conditions-tab">
                                            <form method="post" action="{{ route('saveTerms.page') }}">
                                                @csrf
                                                <textarea name="editor1">{{ @$terms->page_content }}</textarea>
                                                <div class="col-12 px-0 mt-3 text-right">
                                                    <button type="submit" class="btn btn-success update-btn">update</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="privacy_policy" role="tabpanel"
                                            aria-labelledby="privacy_policy-tab">
                                            <form method="post" action="{{ route('savePolicy.page') }}">
                                                @csrf
                                                <textarea name="editor2"> {{ @$policy->page_content }} </textarea>
                                                <div class="col-12 px-0 mt-3 text-right">
                                                    <button type="submit" class="btn btn-success px-5">update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>


    @endsection

    @section('scripts')
        <script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('editor1');
            CKEDITOR.replace('editor2');
        </script>
    @endsection
