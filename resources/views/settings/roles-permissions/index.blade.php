<style>
    .callout:hover { background-color: #f1f1f1; }
    .info, .actions { float: left }
    .info { width: calc(103% - 160px) }
    .actions { margin-top: 10px }
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
            <div class="card">
                <div class="card-body">
                    <p><a href="{{ route('roles-permissions.create') }}" class="btn btn-info"><i class="fas fa-plus"></i> Create Role</a></p>
                    <div class="col-12">
                        @if(session()->has('message'))
                            <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                {!! session()->get('message') !!}
                            </div>
                        @endif
                        @foreach($roles as $role)
                            @if($role->name != 'applicant' && $role->name != 'admin')
                            <div class="callout callout-info row">
                                    <div class="info">
                                        <h5 class="mb-0">{{$role->label}}</h5>
                                    </div>
                                    <div class="actions" style="float: right;">
                                        <a onclick="open_window('{{ route('roles-permissions.show', $role->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-default" title="Show"><i class="fas fa-eye"></i></a>
                                        <a onclick="open_window('{{ route('roles-permissions.edit', $role->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('roles-permissions.destroy', $role->id) }}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this role?');"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                            
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection