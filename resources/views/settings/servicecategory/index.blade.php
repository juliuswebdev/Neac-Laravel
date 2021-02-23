@extends('layouts.dashboard')

@section('name_content')
    Service Category
@endsection

@section('content')
<style>
    .info { float: left }
    .info { width: calc(100% - 140px) }
    .actions { float: right; }
    .actions a { margin-right: 2px; color: #fff; }
    .actions a.btn-default { color: #333; }
</style>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
            <!-- /.card-header -->
                <div class="card-body">
                 
                    <p><a href="{{ route('service-category.create') }}" class="btn btn-info" style="color: #fff;"><i class="fas fa-plus"></i> Add Service Category</a></p>
                 
                    @foreach($service_category as $item)
                    <div class="callout callout-info row">
                        <div class="info">
                            <h5>{{ $item->name }}</h5>
                        </div>
                        <div class="actions">
                            <a href="{{ route('service-category.show', $item->id) }}" class="btn btn-default" title="Show"><i class="fas fa-eye"></i></a>
                       
                            <a href="{{ route('service-category.edit', $item->id) }}" class="btn btn-primary" title="Edit" style="margin-right: 4px;"><i class="fas fa-edit"></i></a>
                          
                        
                            <form action="{{ route('service-category.destroy',$item->id) }}" method="POST" style="float: right">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to delete this form?');"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection