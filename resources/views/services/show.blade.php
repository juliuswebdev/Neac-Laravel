@extends('layouts.dashboard')

@section('name_content')
    {{ $service->name }} [ {{ $service->service_category['name']  }} ]
@endsection

@section('content')
<style>
  h1 { margin-top: 2 0px!important; }
  footer.main-footer,
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
  <!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-8">
            <div class="card card-outline
            @php
            if($service->status == 0) {
                echo 'card-danger';
            } elseif($service->status == 1) {
                echo 'card-success';
            }
            @endphp
            ">
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group">
                        <label for="mother_name">Category: </label>
                        {{ $service->service_category['name']  }}
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Type: </label>
                        {{ $service->type }}
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Name: </label>
                        {{ $service->name }}
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Price: </label>
                        {{ $service->price }}
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Description: </label><br>
                        {!! $service->description !!}
                    </div>
                    <div class="form-group">
                        <label for="mother_name">Sort: </label>
                        {{ $service->sort }}
                    </div>
                    <div class="form-group">
                        @can('service-edit')
                            <a onclick="open_window('{{ route('services.edit',$service->id) }}')" href="#" class="btn btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                        @endcan
                        @can('service-disable-enable')
                            @if( $service->status == 1 )
                            <form action="{{ route('services.deactivate',$service->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('post')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to deactivate this service?');" title="Deactivate"><i class="fas fa-ban"></i></button>
                            </form>
                            @else
                            <form action="{{ route('services.activate',$service->id) }}" method="POST" style="display: inline-block">
                                @csrf
                                @method('post')
                                <button type="submit" class="btn btn-success" onclick="return confirm('Do you really want to activate this service?');" title="Activate"><i class="fas fa-check"></i></button>
                            </form>
                            @endif
                        @endcan

                        @can('service-destroy')
                        <form action="{{ route('services.destroy',$service->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to delete this services?');" title="Activate"><i class="fas fa-trash"></i></button>
                        </form>
                    @endcan
                    </div>
                </div>
            </div>
        </div>
    
        @if($service->state)
        <div class="col-md-4">
            <div class="card card-outline
            @php
            if($service->status == 0) {
                echo 'card-danger';
            } elseif($service->status == 1) {
                echo 'card-success';
            }
            @endphp
            ">
                <div class="card-body">
                    @php $states = explode("\r\n", $service->state); @endphp
                    @foreach($states as $state)
                    {{ $state }}<br>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

@endsection