<style>
    .active td { background-color: #ecfff0; }
    .deactivated td { text-decoration:  line-through; background-color: #ffeff1 }
    
  </style>
@extends('layouts.dashboard')

@section('name_content')
   Employees Current Profiles <a href="{{ route('employees.create') }}" class="btn btn-sm btn-success">Add Employee</a>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
            <div class="card-body">

              <form action="" method="GET" class="row">
                <div class="col-md-3">
                  <input type="text" name="q" class="form-control" placeholder="ex. No#, Name, Email" value="@if(isset($_GET['q'])){{$_GET['q']}}@endif">
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary">Search</button>
                </div>
              </form>

              <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No#</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="width: 180px;">Actions</th>
                    </tr>
                </thead>  
                <tbody>
                @php
                    $count = 0;
                @endphp
                @if(!$employees->isEmpty())
                    @foreach($employees as $user)                     
                          <tr class="@php echo ($user->status == 1) ? 'active' : 'deactivated'; @endphp">
                              @php $count++; @endphp
                              <td>{{ $count }}</td>
                              <td>{{ $user->employee_number }}</td>
                              <td>{{ $user->first_name }}</td>
                              <td>{{ $user->middle_name }}</td>
                              <td>{{ $user->last_name }}</td>
                              <td>{{ $user->email }}</td>
                              <td>{{ $user->user_type }}</td>
                              <td>
                                @can('employee-edit')
                                  <a onclick="open_window('{{ route('employees.edit',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                @endcan

                                @can('employee-delete')
                                  @if( $user->status == 1 )
                                  <form action="{{ route('employees.destroy',$user->id) }}" method="POST" style="display: inline-block">
                                      @csrf
                                      @method('delete')
                                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this user?');" title="Delete {{$user->email}}"><i class="fas fa-trash"></i></button>
                                  </form>
                                  @endif
                                @endcan

                                @can('employee-edit')
                                  <button type="button" class="btn btn-sm btn-warning " data-toggle="modal" data-target="#resetPasswords{{ $count }}"><i class="fas fa-key" style="color: #fff;"> </i> </button>
                                  <div class="modal fade" id="resetPasswords{{ $count }}" tabindex="-1" aria-labelledby="resetPasswords" aria-hidden="true">
                                    <div class="modal-dialog">
                                      <form action="{{ route('employees.resetpassword',$user->id) }}" class="change-password" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Change Password <small>[{{ $user->first_name }} {{ $user->last_name }}]</small></h5>
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">
                                                @csrf
                                                @method('POST')
                                                <div class="row">
                                                  <label class="col-md-3 col-form-label text-md-right">New Password</label>                                   
                                                  <div class="col-md-6">                      
                                                    <input type="password" class="password form-control" name="password" required>
                                                  </div>
                                                  <div class="col-md-3">
                                                    <a href="javascript:void(0)" class="btn btn-info show-hide-password" title="Show Hide"><i class="fas fa-eye-slash"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-warning generate-password" style="color: #fff;" title="Generate Password"><i class="fas fa-key"></i></a>
                                                  </div>
                                                  <div class="col-md-12">
                                                    <div class="alert-change-password"></div>
                                                  </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                @endcan
                                @can('employee-mail')
                                  <a onclick="open_window('{{ route('mail.show',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-info" title="Send Mail"><i class="fas fa-envelope"></i></a>
                                @endcan
                              </td>
                          </tr>
                    @endforeach 
                @else
                    <tr style="color: #212529; background-color: rgba(0,0,0,.075);">
                      <td colspan="8" class="text-center"><strong>No result found!</strong></td>
                    </tr>
                @endif
                </tbody>
              </table>
              @if(!$employees->isEmpty())
                <div class="mt-3">
                  @php $paginator = $employees; @endphp
                  @include('commons.pagination', [$paginator, $count])
                </div>
              @endif
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection