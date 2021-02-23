@extends('layouts.dashboard')

@section('name_content')
    Create Role
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
        <div class="card">
            <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('roles-permissions.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Role Slug</label>
                            <input class="form-control" type="text" name="name" placeholder="It should be small letter and instead of using space use '-' or '_' to separate word" required>
                        </div>
                        <div class="form-group">
                            <label>Role Label</label>
                            <input class="form-control" type="text" name="label" placeholder="" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection