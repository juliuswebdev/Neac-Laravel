@extends('layouts.dashboard')

@section('name_content')
    Currency
@endsection

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-4">
            @if(session()->has('message'))
                <div class="col-md-12">
                <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ session()->get('message') }}
                </div>
                </div>
            @endif
            <div class="card">
            <!-- /.card-header -->

                    @foreach($currency as $item)
                    <form action="{{ route('currency.update', $item->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="card-header">1 {{ $item->code }}</div>
                        <div class="card-body">
                            <div>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="display: inline-block; width: auto;" name="value" value="{{ $item->value }}">&nbsp;&nbsp;Peso
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="display: inline-block; width: auto;" name="additional" value="{{ $item->additional }}">&nbsp;&nbsp;Additional Peso
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" style="display: inline-block; width: auto;" name="vat" value="{{ $item->vat }}">&nbsp;&nbsp;VAT
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        
                    </form>
                    @endforeach
            </div>
        </div>
    </div>
</section>

@endsection