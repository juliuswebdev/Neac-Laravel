<style>
    .callout:hover { background-color: #f1f1f1; cursor: pointer }
    .info, .actions { float: left }
    .info { width: calc(103% - 100px) }
    .actions { margin-top: 10px }
    .tab-pane.active th,
    .tab-pane.active td { background: none; padding: 10px; vertical-align: top; }
</style>
@extends('layouts.dashboard')

@section('name_content')
    Email Settings
@endsection

@section('content')

@php
    $modules_arr = ['Admin - Applicant', 'Admin - Employee', 'Portal', 'Reseller'];
    $r_id = (session()->has('id')) ? session()->get('id') : '';
    $r_module = (session()->has('module')) ? session()->get('module') : 'Admin - Applicant';
@endphp
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    @php $header_count = 0; @endphp
                    @foreach($modules_arr as $item)
                        <li class="nav-item">
                            <a class="nav-link @if($r_module == $item) active @endif" data-toggle="pill" href="#tab-{{ strtolower(str_replace(' ', '-', $item)) }}" role="tab">
                                {{ $item }}
                            </a>
                        </li>
                        @php $header_count++; @endphp
                    @endforeach
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent"> 
                    @php 
                    $body_count = 0;
                    @endphp
                    @foreach($modules_arr as $item)
                        <div class="tab-pane fade @if($r_module == $item) active show @endif" id="tab-{{ strtolower(str_replace(' ', '-', $item)) }}" role="tabpanel">
                            @php $modules = App\EmailSettings::where('module', $item)->get(); @endphp
                            @foreach($modules as $module)
                                <div class="card card-outline card-info">
                                    <div class="card-header p-3">
                                        <a data-toggle="collapse" data-parent="#tab-{{ strtolower(str_replace(' ', '-', $item)) }}" href="#form-{{$module->id}}" class="@if($r_id != $module->id) collapsed @endif" aria-expanded="false" style="border-top: 0;">
                                            {{ $module->description }}<i class="fas fa-angle-down ml-2"></i>
                                        </a>
                                        <a style="float: right" href="javascript:void(0)" onclick="open_window('{{ route('email-settings.show', $module->id) }}')" style="border-top: 0;"><i class="fas fa-eye"></i></a>
                                    </div>
                                    <div id="form-{{$module->id}}" class="card-body panel-collapse in collapse @if($r_id == $module->id) show @endif" style="">
                                        @if(session()->has('message') && $r_module == $item)
                                            <div class="alert alert-success alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                {{ session()->get('message') }}
                                            </div>
                                        @endif
                                        <form action="{{ route('email-settings.update', $module->id) }}" method="POST">
                                            @method('PUT')
                                            @csrf
                                            <input type="hidden" name="module" value="{{ $module->module }}">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <h5>Sending Mail</h5>
                                                    <table style="width: 100%;">
                                                        <tr>
                                                            <th style="width: 100px;">TO:</th>
                                                            <td>[User Email] <small>(Default)</small></td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 100px;">CC:</th>
                                                            <td>
                                                                <input type="text" name="cc_mail" class="form-control" value="{{ $module->cc_mail }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 100px;">BCC:</th>
                                                            <td>
                                                                <input type="text" name="to_mail" class="form-control" value="{{ $module->to_mail }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th style="width: 100px;">Subject:</th>
                                                            <td>
                                                                <input type="text" name="subject_mail" class="form-control" value="{{ $module->subject_mail }}">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small><strong>Note:</strong> For multiple please separate with comma. Ex. juan@gmail.com,delacruz@gmail.com</small></td>
                                                        </tr>
                                                    </table>
                                                    <h5 class="mt-4">Display Variables</h5>
                                                    <table>
                                                        <tr>
                                                            <th style="width: 100px;">Variables: </th>
                                                            <td>
                                                                {$application_number}<br>
                                                                {$employee_number}<br>
                                                                {$first_name}<br>
                                                                {$last_name}<br>
                                                                {$password}<br>
                                                                {$email}<br>
                                                                {$application_status}<br>
                                                                {$application_status_status_message}<br>
                                                                {$form}<br>
                                                                {$email_register_validation_portal_link}<br>
                                                                {$email_reset_password_portal_link}<br>
                                                                {$company}<br>
                                                                {$reseller_code}<br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small><strong>Note:</strong> This can be use to display this variable dynamic in the email</small></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label for="">Header</label>
                                                        <textarea name="header" class="form-control summer_note" rows="4" placeholder="">{{ $module->header }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Body</label>
                                                        <textarea name="body" class="form-control summer_note" rows="4" placeholder="">{{ $module->body }}</textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="">Footer</label>
                                                        <textarea name="footer" class="form-control summer_note" rows="4" placeholder="">{{ $module->footer }}</textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    @php $body_count++; @endphp
                    @endforeach
                </div>
              </div>
              <!-- /.card -->
            </div>
        </div>
    </div>
</section>

@endsection