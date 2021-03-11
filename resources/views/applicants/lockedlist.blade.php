
@extends('layouts.dashboard')

@section('name_content')
   Locked Accounts
@endsection

@section('content')
  <!-- Main content -->

  <section class="content">

    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            
            <table class="table table-bordered table-hover">
              <thead>
                  <tr>
                      
                      <td>Admin User</td>
                      <td>Record Owner</td>
                      <td>Record Type</td>
                      <td>Lock Date</td>
                      <th style="width: 20px;"></th>
                  </tr>
              </thead>  
              <tbody>
                @php
                    $count = 0;
                @endphp
                @if(!$nurses->isEmpty())
                  @foreach($nurses as $user)                     
                    <tr>
                        @php 
                          $count++;
                          $admin_lock = $user->admin_locked($user->lock_user_id);
                        @endphp
                        
                        <td>{{ $admin_lock->first_name }} {{ $admin_lock->middle_name }} {{ $admin_lock->last_name }} <small>[{{ $admin_lock->email }}]</small></td>
                        <td>{{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} <small>[{{ $user->email }}]</small></td>
                        <td>Edit Profile</td>
                        <td>{{ $user->lock_date }}</td>

                        <td style="width: 20px;">
                          @can('applicant-edit')
                          <form action="{{ route('applicants.lockedlist.unlock',$user->id) }}" method="POST">
                            @csrf
                            @method('post')
                            <button class="btn btn-sm btn-danger" title="Click to Unlock" onclick="confirm('Are you sure you want to unlock this item?')"><i class="fas fa-times-circle"></i></button>
                          </form>
                          @endcan
                        </td>
                    </tr>
                  @endforeach
                @else
                    <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                      <td colspan="7" class="text-center"><strong>No result found!</strong></td>
                    </tr>
                @endif
              </tbody>
          </table>
          @if(!$nurses->isEmpty())
            <div class="mt-3">
              @php $paginator = $nurses; @endphp
              @include('commons.pagination', [$paginator,$count])
            </div>
          @endif

          
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->

@endsection