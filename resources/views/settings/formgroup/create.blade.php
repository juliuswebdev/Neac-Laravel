@extends('layouts.dashboard')

@section('name_content')
    Add Form Group
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
            <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('forms.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Form Group Name</label>
                            <input class="form-control" type="text" name="name" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label>Form Group Description</label>
                            <textarea class="form-control" rows="3" name="description" placeholder=""></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection