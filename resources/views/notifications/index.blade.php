@extends('layouts.dashboard')

@section('name_content')
@endsection
@section('content')
    <section class="content">
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
                            try {
                        @endphp
                        @if($notification->module == 'register')
                        <div class="dropdown-item  text-sm">
                            <i class="fas fa-user mr-2"></i>{{ $notification->messages }}<br>
                            <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        @elseif($notification->module == 'forms')
                        <a onclick="open_window('{{ $notification->url }}')" href="javascript:void(0)" class="dropdown-item text-sm">
                            <i class="far fa-folder mr-2"></i> {{ $notification->messages }} <small><i class="ml-2 fas fa-external-link-alt"></i></small><br>
                            <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;
                            <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->first_name }}</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        @else
                        <div class="dropdown-item  text-sm">
                            <i class="fas fa-envelope mr-2"></i> {{ $notification->messages }}<br>
                            <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;
                            <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->first_name }}</span>
                        </div>
                        <div class="dropdown-divider"></div>
                        @endif
                        @php
                        } catch(Exception $e) {
                            echo 'Data is deleted in the database!';
                        }
                        @endphp
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