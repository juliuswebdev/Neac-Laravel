@extends('layouts.dashboard')

@section('name_content')
   Services
@endsection

@section('content')
<style>
    .active td { background-color: #ecfff0; }
    .deactivated td {  background-color: #ffeff1 }
  </style>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="GET" class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" name="q" class="form-control" placeholder="Category, Type, Name etc." value="@if(isset($_GET['q'])){{$_GET['q']}}@endif">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                    <table class="table table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>           
                        <tbody>
                        @php $count = 0 @endphp
                        @if(!$services->isEmpty())
                            @foreach ($services as $service)
                            <tr class="@php echo ($service->status == 1) ? 'active' : 'deactivated'; @endphp">
                                @php $count++ @endphp
                                <td>{{ $count }}</td>
                                <td>{{ $service->service_category['name'] }}</td>
                                <td>{{ $service->type }}</td>
                                <td>{{ $service->name }}</td>
                                <td>$ {{ $service->price }}</td>
                                <td>
                                    <a onclick="open_window('{{ route('services.show',$service->id) }}')" href="#" class="btn btn-sm btn-default" title="View"><i class="fas fa-eye"></i></a>
                                    
                                    <a onclick="open_window('{{ route('services.edit',$service->id) }}')" href="#" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                
                                    @if( $service->status == 1 )
                                    <form action="{{ route('services.deactivate',$service->id) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('post')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to deactivate this service?');" title="Deactivate"><i class="fas fa-ban"></i></button>
                                    </form>
                                    @else
                                    <form action="{{ route('services.activate',$service->id) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('post')
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Do you really want to activate this service?');" title="Activate"><i class="fas fa-check"></i></button>
                                    </form>
                                    @endif

                                    <form action="{{ route('services.destroy',$service->id) }}" method="POST" style="display: inline-block">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this services?');" title="Activate"><i class="fas fa-trash"></i></button>
                                    </form>
                                
                                </td>
                            </tr>
                            @endforeach 
                        @else
                            <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                                <td colspan="6" class="text-center"><strong>No result found!</strong></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if(!$services->isEmpty())
                        <div class="mt-3">
                        @php $paginator = $services; @endphp
                        @include('commons.pagination', [$paginator,$count])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection