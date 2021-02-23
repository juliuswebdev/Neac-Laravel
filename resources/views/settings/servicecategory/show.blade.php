@extends('layouts.dashboard')

@section('name_content')
    Show [{{ $service_category->name }}]
@endsection
@php
    $document_path = '/documents/';
  @endphp
@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
            <!-- /.card-header -->
                <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Service Category Name</label>
                            {{ $service_category->name }}
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image-s">Image</label>
                                    @if($service_category->image)
                                        <img src="{{ $document_path }}{{ $service_category->image }}" alt="" style="margin: 0 auto; height: 100px;"><br>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo-s">Logo</label>
                                    @if($service_category->logo)
                                        <img src="{{ $document_path }}{{ $service_category->logo }}" alt="" style="margin: 0 auto; height: 100px;"><br>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Service Category Description</label>
                            {!! $service_category->description !!}
                        </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection