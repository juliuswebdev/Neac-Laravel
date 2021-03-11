@extends('layouts.dashboard')

@section('name_content')
@endsection
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body">
                <form action="" class="mb-0" method="GET">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Search by Date Range</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="date_filter" class="form-control date_range_picker_js2" placeholder="Date Range" value="@php echo isset($_GET['date_filter']) ? $_GET['date_filter'] : '' @endphp" autocomplete="off" autocomplete="off">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a class="btn btn-danger" href="{{ route('notifications.index') }}">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                <!-- /.card-header -->
                    <div class="card-body">
                    @php
                        $count = 0;
                    @endphp
                    @foreach($notifications as $notification)
                        @php
                            $count++;
                        
                        @endphp
                        
                            @if($notification->module == 'register')
                            <div class="dropdown-item  text-sm">
                                <i class="fas fa-user mr-2"></i><h6 class="lib">{{ $notification->messages }}</h6 ><br>
                                <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>
                            </div>
                            <div class="dropdown-divider"></div>
                            @elseif($notification->module == 'forms')
                            <a onclick="open_window('{{ $notification->url }}')" href="javascript:void(0)" class="dropdown-item text-sm">
                                <i class="far fa-folder mr-2"></i> <h6 class="lib">{{ $notification->messages }}</h6 > <small><i class="ml-2 fas fa-external-link-alt"></i></small><br>
                                <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @if(isset($notification->user))
                                <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->email }} [{{$notification->user->first_name.' '.$notification->user->last_name}}]</span>
                                @endif
                            </a>
                            <div class="dropdown-divider"></div>
                            @else
                            <div class="dropdown-item  text-sm">
                                <i class="fas fa-envelope mr-2"></i> <h6 class="lib">{{ $notification->messages }}</h6 ><br>
                                <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @if(isset($notification->user))
                                <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->email }} [{{$notification->user->first_name.' '.$notification->user->last_name}}]</span>
                                @endif
                            </div>
                            <div class="dropdown-divider"></div>
                            @endif

                

                    @endforeach
                    @if(!$notifications->isEmpty())
                        <div class="mt-3">
                        @php $paginator = $notifications; @endphp
                        @include('commons.pagination', [$paginator, $count])
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection