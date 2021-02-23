<style>
  h1 { margin-top: 2 0px!important; }
  footer.main-footer,
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
@extends('layouts.dashboard')

@section('name_content')
    Roles and Permissions
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="card card-outline card-success">
                <div class="card-header">
                    <h2 class="card-title">{{$role->label}}</h2>
                </div>
                <div class="card-body">
                    @if(session()->has('message'))
                        <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            {!! session()->get('message') !!}
                        </div>
                    @endif
                    <form action="{{ route('roles-permissions.update', $role->id) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="exampleInputEmail1">Role Slug</label>
                            <input class="form-control" type="text" name="name" placeholder="It should be small letter and instead of using space use '-' or '_' to separate word" value="{{$role->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>Role Label</label>
                            <input class="form-control" type="text" name="label" value="{{$role->label}}" placeholder="" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
            <div class="card card-outline card-success">
                <div class="card-body">
                    <ul style="column-count:4">
                    @foreach($permissions as $permission)
                        @php
                            $checked = false;
                            $active = true;
                            foreach($role_has_permissions as $per) {
                                if($role->id == $per->role_id && $permission->id == $per->permission_id) {
                                    $checked = true;
                                }
                            }
                        @endphp
                        <li>
                            <input type="checkbox" id="{{ $permission->name }}-{{ $permission->id }}" class="input-permission" name="permission" data-role-id="{{ $role->id }}" data-permission-id="{{ $permission->id }}"
                                @if($checked)
                                checked
                                @endif
                            >&nbsp;
                            <label for="{{ $permission->name }}-{{ $permission->id }}">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</label>
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection