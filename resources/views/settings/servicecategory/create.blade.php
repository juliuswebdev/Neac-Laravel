@extends('layouts.dashboard')

@section('name_content')
    Add Service Category
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('message') }}
                </div>
            @endif
            <div class="card">
            <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('service-category.store') }}" method="POST"  method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Service Category Name</label>
                            <input class="form-control" type="text" name="name" placeholder="" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image-s">Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="image" id="image-s" value="" class="custom-file-input">
                                            <label class="custom-file-label" for="exampleInputFile" accepts=".png, .jpg, .JPEG, .gif">Choose Image</label>
                                        </div>
                                    </div>
                                    <small>
                                        <strong>accepts: </strong> .png, .jpg, .JPEG, .gif
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="logo-s">Logo</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" name="logo" id="logo-s" value="" class="custom-file-input">
                                            <label class="custom-file-label" for="exampleInputFile" accepts=".png, .jpg, .JPEG, .gif">Choose Logo</label>
                                        </div>
                                    </div>
                                    <small>
                                        <strong>accepts: </strong> .png, .jpg, .JPEG, .gif
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Service Category Description</label>
                            <textarea class="form-control summer_note" rows="3" name="description" placeholder=""></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection