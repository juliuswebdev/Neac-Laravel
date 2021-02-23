@extends('layouts.dashboard')

@section('content')

<section class="content">
    <div class="resellers-area">
        <div class="modal fade" id="resellers-modal" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 1400px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Resellers</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-stripe table-hover table-home">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Code</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Date Account Created</th>
                                    <th style="width: 40px">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count4 = 1; @endphp
                                @foreach($resellers as $data_user)
                                    <tr style="cursor: pointer">
                                        <th>{{ $count4++ }}</th>
                                        <td><a href="/reseller?code={{ $data_user->reseller_code }}">{{ $data_user->reseller_code }}</a></td>
                                        <td>{{ $data_user->first_name }}</td>
                                        <td>{{ $data_user->middle_name }}</td>
                                        <td>{{ $data_user->last_name }}</td>
                                        <td>{{ $data_user->created_at }}</td>
                                        <td>{{ $data_user->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="new-applicant-area">
        <div class="modal fade" id="new-applicant-modal" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 1400px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">New Applicants</h4><small class="badge badge-primary">New</small>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-stripe table-hover table-home">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th style="width: 40px">Email</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Date Account Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count3 = 1; @endphp
                                @foreach($new_applicant as $data_user)
                                    <tr  style="cursor: pointer">
                                        <th>{{ $count3++ }}</th>
                                        <td>{{ $data_user->email }}</td>
                                        <td>{{ $data_user->first_name }}</td>
                                        <td>{{ $data_user->middle_name }}</td>
                                        <td>{{ $data_user->last_name }}</td>
                                        <td>{{ $data_user->created_at }}</td>
                                        <td>
                                            @can('applicant-show')
                                            <a onclick="open_window('{{ route('applicants.show',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-default" title="Show"><i class="fas fa-eye"></i></a>
                                            @endcan

                                            @can('applicant-edit')
                                            <a onclick="open_window('{{ route('applicants.edit',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="employees-area">
        <div class="modal fade" id="employees-modal" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 1400px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Employees</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-stripe table-hover table-home">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Date Account Created</th>
                                    <th style="width: 40px">Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $count4 = 1; @endphp
                                @foreach($employees as $data_user)
                                    <tr  style="cursor: pointer">
                                        <th>{{ $count4++ }}</th>
                                        <td>{{ $data_user->first_name }}</td>
                                        <td>{{ $data_user->middle_name }}</td>
                                        <td>{{ $data_user->last_name }}</td>
                                        <td>{{ $data_user->created_at }}</td>
                                        <td>{{ $data_user->email }}</td>
                                        <td>{{ $data_user->user_type }}</td>
                                        <td>
                                            @can('employee-edit')
                                            <a onclick="open_window('{{ route('employees.edit',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="" style="-ms-flex: 0 0 20%;flex: 0 0 20%;max-width: 20%; padding: 0 7.5px;">
            <div class="info-box" onclick="window.location.href='/transactions?status=2'">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-dollar-sign"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Successful Transaction</span>
                    <span class="info-box-number">{{ $successful_transaction }} <small>[Finance Approved]</small></span>
                </div>
            </div>
        </div>
        <div class="" style="-ms-flex: 0 0 20%;flex: 0 0 20%;max-width: 20%; padding: 0 7.5px;">
            <div class="info-box mb-3" onclick="window.location.href='/transactions?status=1'">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>
                <div class="info-box-content">
                <span class="info-box-text">Active Carts</span>
                <span class="info-box-number">{{ $active_carts }}</span>
                </div>
            </div>
        </div>
        <div class="" style="-ms-flex: 0 0 20%;flex: 0 0 20%;max-width: 20%; padding: 0 7.5px;">
            <div class="info-box mb-3" data-toggle="modal" data-target="#new-applicant-modal">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">New Applicants </span>
                    <span class="info-box-number">{{ $new_applicant->count() }}&nbsp;&nbsp;<small>[last 14 days]</small></span>
                </div>
            </div>
        </div>
        <div class="" style="-ms-flex: 0 0 20%;flex: 0 0 20%;max-width: 20%; padding: 0 7.5px;">
            <div class="info-box mb-3" data-toggle="modal" data-target="#employees-modal">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                <span class="info-box-text">Employees</span>
                <span class="info-box-number">{{ $employees->count() }}</span>
            </div>
            </div>
        </div>
        <div class="" style="-ms-flex: 0 0 20%;flex: 0 0 20%;max-width: 20%; padding: 0 7.5px;">
            <div class="info-box mb-3" data-toggle="modal" data-target="#resellers-modal">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                <span class="info-box-text">Resellers</span>
                <span class="info-box-number">{{ $resellers->count() }}</span>
            </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Applicant Statistics
                    <div class="card-tools">
                        <select class="form-control" name="applicant_year" id="applicant_year">
                        @php
                        $this_year = date("Y");
                        $html_output = "";
                        for ($year = $this_year; $year >= $this_year - 5; $year--) {
                        @endphp
                            <option value="{{ $year }}">{{ $year }}</option>
                        @php
                        }
                        @endphp
                        </select>
                    </div>
                </div>
                <div class="card-body">
                        <canvas id="salesChart" height="250"></canvas>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Active Testimonials</div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px;"><small>#</small></th>
                                        <th>Category</th>
                                        <th style="width: 200px;"><small>Email</small></th>
                                        <th><small>Subject</small></th>
                                    </tr>
                                </thead>  
                                <tbody>
                                @php
                                    $testimonial_count = 1;
                                @endphp
                                @foreach($testimonials as $testimonial)
                                    <tr>
                                        <td>{{ $testimonial_count++ }}</td>
                                        <td>
                                            @if($testimonial->category == 0)
                                            NCLEX Applicants
                                            @elseif($testimonial->category == 1)
                                            HAAD, DHA, Saudi, Oman, Qatar Applicants
                                            @elseif($testimonial->category == 2)
                                            Licensed Applicants
                                            @elseif($testimonial->category == 3)
                                            Video Testimonials
                                            @endif
                                        </td>
                                        <td>
                                            @if($testimonial->user)
                                            {{ $testimonial->user->first_name }} {{ $testimonial->user->last_name }}<small><br>{{ $testimonial->user->email }}</small>
                                            @endif
                                        </td>
                                        <td><div style="max-height: 100px; overflow: hidden;">{{ $testimonial->subject }}</div></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center clearfix">
                            <a href="{{ route('testimonials.index') }}">View All Testimonials</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Services</div>
                        <div class="card-body p-0">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Tags</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($shopify_products as $product)
                                        <tr>
                                            <td>
                                                @if(isset($product->image->src))
                                                <img src="{{ $product->image->src }}" alt="" width="50">
                                                @endif
                                            </td>
                                            <td>{{ $product->tags }}</td>
                                            <td>{{ $product->title }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>          
                            </table>
                        </div>
                        <div class="card-footer text-center clearfix">
                            <a href="https://medexamscenter.com/pages/neac-services">View All Services</a>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="modal fade" id="inquiry-modal" aria-hidden="true">
                        <div class="modal-dialog" style="max-width: 1400px;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Inquiry</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-stripe table-hover table-home">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>First Name</th>
                                                <th>Middle Name</th>
                                                <th>Last Name</th>
                                                <th>Date Account Created</th>
                                                <th style="width: 40px">Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count2 = 1; @endphp
                                            @foreach($inquiry as $data_user)
                                                <tr style="cursor: pointer">
                                                    <th>{{ $count2++ }}</th>

                                                    <td>{{ $data_user->first_name }}</td>
                                                    <td>{{ $data_user->middle_name }}</td>
                                                    <td>{{ $data_user->last_name }}</td>
                                                    <td>{{ $data_user->created_at }}</td>
                                                    <td>{{ $data_user->email }}</td>
                                                    <td>
                                                        @can('applicant-show')
                                                        <a onclick="open_window('{{ route('applicants.show',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-default" title="Show"><i class="fas fa-eye"></i></a>
                                                        @endcan

                                                        @can('applicant-edit')
                                                        <a onclick="open_window('{{ route('applicants.edit',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    Inquiry
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#inquiry-modal">
                        <span class="badge badge-success ml-2">
                        {{ $count2 - 1 }}
                        </span>
                    </a>

                </div>
            </div>
            @foreach($application_status as $status)
                @if($status->application_status_report->count() > 0)
                    <div class="card">
                        <div class="card-header">
                            {{ $status->name }}
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            
                            @foreach($status->application_status_report as $report)
                                @php $count1 = 0; @endphp
                                <div class="status_report " style="margin-bottom: 20px;">{{ $report->application_status_message }}<br><small>[{{ $report->label }}]</small>
                                    <div class="modal fade" id="dashboard-status-{{ $report->id }}-modal" aria-hidden="true">
                                        <div class="modal-dialog" style="max-width: 1400px;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">{{ $report->application_status_message }}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-stripe table-hover table-home">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 10px">#</th>
                                                                <th>First Name</th>
                                                                <th>Middle Name</th>
                                                                <th>Last Name</th>
                                                                <th>{{ $report->label }}</th>
                                                                <th style="width: 40px">Email</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        @foreach($application_status_report as $data_user)
                                                            @if($report->application_status_message == $data_user->application_status_message)
                                                                    @php $count1++; @endphp
                                                                    <tr style="cursor: pointer">
                                                                        <th>{{ $count1 }}</th>
                                                                        <td>{{ $data_user->first_name }}</td>
                                                                        <td>{{ $data_user->middle_name }}</td>
                                                                        <td>{{ $data_user->last_name }}</td>
                                                                        <td>{{ $data_user->post }}</td>
                                                                        <td>{{ $data_user->email }}</td>
                                                                        <td>
                                                                            @can('applicant-show')
                                                                            <a onclick="open_window('{{ route('applicants.show',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-default" title="Show"><i class="fas fa-eye"></i></a>
                                                                            @endcan

                                                                            @can('applicant-edit')
                                                                            <a onclick="open_window('{{ route('applicants.edit',$data_user->id) }}')" href="javascript:void(0)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                                            @endcan
                                                                        </td>
                                                                    </tr>

                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($count1 > 0)
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#dashboard-status-{{ $report->id }}-modal">
                                        <span class="badge badge-success ml-2">
                                        {{ $count1 }}
                                        </span>
                                    </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach


        </div>
    </div>
</section>

@endsection
