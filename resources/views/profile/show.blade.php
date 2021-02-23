@extends('layouts.dashboard')

@section('name_content')
    Show Profile {{ $user->first_name }} {{ $user->middle_name }} {{ $user->last_name }} <small>({{$user->email}})</small> <small><a href="{{ route('profile.edit') }}" class="font-sm"><i class="fas fa-edit"></i></a></small>
@endsection
@section('content')
  <!-- Main content -->
  @php
    $document_path = '/documents/';
  @endphp
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-info">
          <div class="card-header">
            <h3 class="card-title">Basic Information</h3>
            <div class="card-tools">
              <strong>Role: </strong>
              @foreach($roles as $role)
                  @php echo ($user->user_type == $role->name) ? $role->label  : ''; @endphp
              @endforeach
            </div>
          </div>
          <div class="card-body">
                <div class="form-group">
                  <div class="form-content">
                    <img style="max-width: 150px; margin-bottom: 10px;" src="{{ $document_path }}{{ $user->employee->image }}" alt="">
                  </div>
                </div>

                <div class="form-group">
                  <label for="first_name">First Name</label>
                  <div class="form-content">{{ $user->first_name }}</div>
                </div>

                <div class="form-group">
                  <label for="middle_name">Middle Name</label>
                  <div class="form-content">{{ $user->middle_name }}</div>
                </div>

                <div class="form-group">
                  <label for="last_name">Last Name</label>
                  <div class="form-content">{{ $user->last_name }}</div>
                </div>

                <div class="form-group">
                  <label for="alternate_email">Alternative Email Address</label>
                  <div class="form-content">{{ $user->employee->alternate_email }}</div>
                </div>

                <div class="form-group">
                  <label for="birth_date">Gender</label>
                  <div class="form-content">
                  @if($user->employee->gender == 1)
                   Male
                  @elseif($user->employee->gender == 2)
                   Female
                  @endif
                  </div>
                </div>

                <div class="form-group">
                  <label for="birth_date">Date of Birth</label>
                  <div class="form-content">{{ $user->employee->birth_date }}</div>
                </div>

                <div class="form-group">
                  <label for="birth_date">Citizenship</label>
                  <div class="form-content">{{ $user->employee->citizenship }}</div>
                </div>

                <div class="form-group">
                  <label for="telephone_number">Telephone Number</label>
                  <div class="form-content">{{ $user->employee->telephone_number }}</div>
                </div>

                <div class="form-group">
                  <label for="mobile_number">Mobile Number</label>
                  <div class="form-content">{{ $user->employee->mobile_number }}</div>
                </div>

                <div class="form-group">
                  <label for="home_address">Home Address</label>
                  <div class="form-content">{{ $user->employee->home_address }}</div>
                </div>

                <div class="form-group">
                  <label for="city">City</label>
                  <div class="form-content">{{ $user->employee->city }}</div>
                </div>

                <div class="form-group">
                  <label for="state">State/Province</label>
                  <div class="form-content">{{ $user->employee->state }}</div>
                </div>

                <div class="form-group">
                  <label for="postal_code">Zip/Postal Code</label>
                  <div class="form-content">{{ $user->employee->postal_code }}</div>
                </div>

                <div class="form-group">
                  <label for="country">Country</label>  
                  <div class="form-content">{{ $user->employee->country }}</div>
                </div>
            </form>
          </div>
        </div>
      </div>
  </section>

@endsection