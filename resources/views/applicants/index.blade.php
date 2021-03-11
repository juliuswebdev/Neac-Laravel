
@extends('layouts.dashboard')

@section('name_content')
   Applicants <a href="{{ route('applicants.create') }}" class="btn btn-sm btn-success">Add Applicant</a>
@endsection

@section('content')
  <!-- Main content -->

  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card card-outline card-info">
        <div class="card-header"><h3 class="card-title">Advance Search</h3></div>
          <div class="card-body">
            <form action="" method="GET" class="mb-0">

              <div class="row mb-1">
                <div class="col-md-4" style="border-right: 1px solid #ccc;">
                  <div class="form-group">
                    <label for="" style="min-height: 50px;">Results are based on tagging/fillup by the users in application form, registration and profile</label>
                    <input type="text" class="form-control" name="q" placeholder="Ex. Juan Dela Cruz" value="@if(isset($_GET['q'])){{$_GET['q']}}@endif">
                  </div>
                </div>
                <div class="col-md-4" style="border-right: 1px solid #ccc;">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="" style="min-height: 50px;">Specific Searching. Left part will be the column and rightside is the value</label>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        @php $ref = (isset($_GET['ref'])) ? $_GET['ref'] : ''; @endphp
                        <select class="form-control" name="ref" id="ref">
                          <option selected disabled>Select Reference</option>
                          <option value="users.email" @if( $ref == 'users.email' ) selected @endif>Email</option>
                          <option value="users.first_name"  @if( $ref == 'users.first_name' ) selected @endif>First Name</option>
                          <option value="users.middle_name"  @if( $ref == 'users.middle_name' ) selected @endif>Middle Name</option>
                          <option value="users.last_name"  @if( $ref == 'users.last_name' ) selected @endif>Last Name</option>
                          <option value="profiles.application_number"  @if( $ref == 'profiles.application_number' ) selected @endif>Application Number</option>
                          @foreach($post_dropdown as $dropdown)
                            <option value="{{ $dropdown->id }}" @if( $ref == $dropdown->id ) selected @endif>{{ $dropdown->label }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" class="form-control" id="ref_val" name="ref_val" value="@if(isset($_GET['ref_val'])){{$_GET['ref_val']}}@endif" @if(!isset($_GET['ref_val'])) 'disabled' @endif>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="" style="min-height: 50px;">Application Status</label>
                    @php
                      $application_status_dropdown_val = isset($_GET['application_status']) ? $_GET['application_status'] : '';
                    @endphp
                    <select name="application_status" id="" class="form-control">
                        <option selected disabled>Select Status</option>
                        <option value="0-0"  @if($application_status_dropdown_val == '0-0') selected @endif>Inquiry</option>
                        @foreach($application_status_dropdown as $status)
                          @foreach($status->application_status_report as $form_input)
                            @php $drp_val = $status->id.'-'.$form_input->id; @endphp
                            <option value="{{ $drp_val }}" @if($application_status_dropdown_val == $drp_val) selected @endif>{{ $status->name . ' - ' . $form_input->application_status_message }}</option>
                          @endforeach
                        @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Search</button>
              <a href="/applicants" class="btn btn-danger">Clear</a>
            </form>
          </div>
        </div>
      </div>
    </div>
   
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            
            <table class="table table-bordered table-hover">
              <thead>
                  <tr>
                      <th style="width: 10px;">#</th>
                      <td>No#</td>
                      <th>Email</th>
                      <th>First Name</th>
                      <th>Middle Name</th>
                      <th>Last Name</th>
                      <th style="width: 210px;">Actions</th>
                  </tr>
              </thead>  
              <tbody>
                @php
                    $count = 0;
                @endphp
                @if(!$nurses->isEmpty())
                  @foreach($nurses as $user)                     
                    <tr class="@php echo ($user->approval == 1) ? 'active' : 'deactivated'; @endphp">
                        @php $count++ @endphp
                        <td>{{ $count }}</td>
                        <td>{{ $user->application_number }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->middle_name }}</td>
                        <td>{{ $user->last_name }}</td>

                        <td style="width: 210px;">
                          @can('applicant-show')
                          <a onclick="open_window('{{ route('applicants.show',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-default" title="Show"><i class="fas fa-eye"></i></a>
                          @endcan

                          @can('applicant-edit')
                          <a onclick="open_window('{{ route('applicants.edit',$user->id) }}')" href="javascript:void(0)" class="btn-edit-lock btn btn-sm @php echo ($user->lock_user_id) ? 'btn-default' : 'btn-primary'; @endphp" title="@php echo ($user->lock_user_id) ? 'Account locked!' : 'Edit'; @endphp"><i class="fas fa-edit"></i></a>
                          @endcan

                          
                          @can('applicant-delete')
                                <form action="{{ route('applicants.destroy',$user->id) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Do you really want to delete this user?');" title="Delete {{ $user->email }}"><i class="fas fa-trash"></i></button>
                                </form>
                          @endcan

                          @can('applicant-edit')
                          <button type="button" class="btn btn-sm btn-warning " data-toggle="modal" data-target="#resetPasswords{{ $count }}"><i class="fas fa-key" style="color: #fff;"> </i> </button>
                            <div class="modal fade resetPassword-modal" id="resetPasswords{{ $count }}" tabindex="-1" aria-labelledby="resetPasswords" aria-hidden="true">
                              <div class="modal-dialog">
                                <form action="{{ route('applicants.resetpassword',$user->id) }}" class="change-password" method="POST">
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

                          @can('applicant-mail')
                          <a onclick="open_window('{{ route('mail.show',$user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-info" title="Send Mail"><i class="fas fa-envelope"></i></a>
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


