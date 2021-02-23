<style>
    .callout:hover { background-color: #f1f1f1; cursor: pointer }
    .info, .actions { float: left }
    .info { width: calc(103% - 160px) }
    .actions { margin-top: 10px }
</style>
@extends('layouts.dashboard')

@section('name_content')
    Application Status
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
            <!-- /.card-header -->
                <div class="card-body">
                    <p><a href="{{ route('applications.create') }}" class="btn btn-info" style="color: #fff;"><i class="fas fa-plus"></i> Add Application Status</a></p>
                    <div class="col-12">
                        @foreach($application_status as $item)
                        <div class="callout callout-info row">
                            <div class="info">
                                <h5 class="mb-0">{{ $item->name }} &nbsp;&nbsp;&nbsp;<small style="font-size: 60%;">Counts: {{ $item->inputs->count() }}</small></h5>
                                <p><small>{{ $item->description }}</small></p>
                            </div>
                            <div class="actions">
                                @can('forms-show')
                                    <a onclick="open_window('{{ route('applications.show', $item->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-default" title="Show"><i class="fas fa-eye"></i></a>
                                @endcan
                                @can('forms-edit')
                                    <a onclick="open_window('{{ route('applications.edit', $item->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan
                                <form action="{{ route('applications.destroy',$item->id) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this application status?');"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection