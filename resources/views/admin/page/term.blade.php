@extends('layouts.main')

@if($terms->page_name == 'terms-and-conditions')
@section('title', 'Terms and conditions')
@else
@section('title', 'Privacy Policy')
@endif

@section('content')
<div class="container-fluid mt-1">
    {!!$terms->page_content!!}
</div>
@endsection