<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>NEAC</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!--Data Tables-->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="{{ asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
  <link rel="icon" href="{{ asset('img/icon.png') }}" sizes="32x32" />
  @if(\Request::is('forms/*') || \Request::is('applications/*'))
  <style>
    .acc_head:before,
    .acc_head:after { display: table; content: ''; }
    .acc_head:after { clear: both }
  </style>
  @endif
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">{{ $bell_notifications->count() }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notifications">
          <span class="dropdown-item dropdown-header">{{ $bell_notifications->count() }} Notifications</span>
          <div class="dropdown-divider"></div>
          @foreach($bell_notifications as $notification)
            @php
              try {
            @endphp
            @if($notification->module == 'register')
            <a href="javascript:void(0)" class="dropdown-item" title="{{ $notification->messages }}">
              <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis; display: block; "><i class="fas fa-user mr-2"></i>{{ $notification->messages }}</span>
              <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>
            </a>
            <div class="dropdown-divider"></div>
            @elseif($notification->module == 'forms')
            <a onclick="open_window('{{ $notification->url }}')" href="javascript:void(0)" class="dropdown-item" title="{{ $notification->messages }}">
              <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis; display: block; "><i class="far fa-folder mr-2"></i>{{ $notification->messages }}</span>
              <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;
              <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->email }}</span>
            </a>
            <div class="dropdown-divider"></div>
            @elseif($notification->module == 'add_cart')
            <a onclick="open_window('{{ $notification->url }}')" href="javascript:void(0)" class="dropdown-item" title="{{ $notification->messages }}">
              <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis; display: block; "><i class="fas fa-shopping-cart mr-2"></i>{{ $notification->messages }}</span>
              <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;
              <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->email }}</span>
            </a>
            <div class="dropdown-divider"></div>
            @else
            <a href="javascript:void(0)" class="dropdown-item" title="{{ $notification->messages }}">
              <span style="white-space: nowrap; overflow: hidden;text-overflow: ellipsis; display: block; "><i class="fas fa-envelope mr-2"></i>{{ $notification->messages }}</span>
              <span class="text-muted text-xs"><i class="fas fa-clock mr-2"></i>{{ Helper::time_elapsed_string($notification->created_at) }}</span>&nbsp;&nbsp;
              <span class="text-muted text-xs"><i class="fas fa-user mr-2"></i>{{ $notification->user->email }}</span>
            </a>
            <div class="dropdown-divider"></div>
            @endif
            @php
              } catch(Exception $e) {
                echo 'Data is deleted in the database!';
              }
            @endphp

          @endforeach
          <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  @include('layouts.sidebar')
  <div class="content-wrapper" >
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            @if (trim($__env->yieldContent('name_content')))
                <h1>@yield('name_content')</h1>
            @endif
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content --> 
        @yield('content')  
    </div>
<!-- ./wrapper -->

<footer class="main-footer">
  <div class="float-right d-none d-sm-block">
    <b>Version</b> 3.0.3
  </div>
  <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">NEAC</a>.</strong> All rights
  reserved.
</footer>

<!--Data Table-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>


<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<!-- page script -->
<!-- <script src="https://sdks.shopifycdn.com/js-buy-sdk/1.11.0/index.umd.min.js"></script> -->
<script src="https://sdks.shopifycdn.com/js-buy-sdk/2.11.0/index.unoptimized.umd.min.js"></script>
<script src="{{ asset('/js/script.js') }}"></script>
@if(\Request::is('transactions'))
  <script src="{{ asset('/js/transaction.js') }}"></script>
@elseif(\Request::is('home'))
  <script src="{{ asset('/js/home.js') }}"></script>
@elseif(\Request::is('forms/*') || \Request::is('applications/*') || \Request::is('applicant-profile-form') )

  <script>

      $(document).ready(function(){
          $('#add_field').submit(function(e){
              e.preventDefault();
              var select = $(this).find('select option:selected'),
                  select_val = select.val(),
                  select_text = select.text();
              var csrf = '@csrf';
              var method = '@method('post')';
              var action = '{{ route('input.store') }}';

              @if(isset($forms->id))
                var form_group_id = '{{ $forms->id }}';
              @endif
              @if(isset($application_status->id))
                var form_group_id = '{{ $application_status->id }}';
              @endif
              @if(\Request::is('applicant-profile-form'))
                var form_group_id = 13;
              @endif



              var settings = '';
              var html = '';
              
                if(select_val == 'select' || select_val == 'checkbox' || select_val == 'radio') {
                  settings = `
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Items</label><br>
                                <small style="display: block; line-height: 1.2; margin-bottom: 10px;">
                                  <strong>Instruction:</strong>
                                  Enter each choice on a new line.<br>
                                  For more control, you may specify both a value and label like this:<br>
                                  red : Red<br>
                                </small>
                                <textarea type="text" class="form-control" name="settings" style="min-height: 200px; max-height: 600px;"></textarea>
                            </div>
                        </div>
                  `;
                } else if(select_val == 'number') {
                  settings = `
                        <div class="col-md-6">
                            <div class="row">
                              <div class="col-md-6 form-group">
                                  <label>Minimum</label>
                                  <input type="number" class="form-control" name="minimum" value="0" required>
                              </div>
                              <div class="col-md-6 form-group">
                                  <label>Maximum</label>
                                  <input type="number" class="form-control" name="maximum" value="1" required>
                              </div>
                            </div>
                        </div>
                  `;
                } else if(select_val == 'multiple_file' || select_val == 'multiple_image') {
                    settings = `
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Items</label><br>
                                  <small style="display: block; line-height: 1.2; margin-bottom: 10px;">
                                    <strong>Instruction:</strong>
                                    Enter each choice on a new line.<br>
                                    For more control, you may specify accept file extension like this:<br>
                                    .jpg, .docx, .txt<br>
                                  </small>
                                  <textarea type="text" class="form-control" name="settings" style="min-height: 200px; max-height: 600px;"></textarea>
                              </div>
                          </div>
                    `;
                } else if(select_val == 'date_picker') {
                      settings = `<div class="col-md-3">
                                            <div class="form-group">
                                                <label>Disable Previous Date</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" name="settings" value="1" type="checkbox">
                                                    <label class="form-check-label">Yes/No</label>
                                                </div>
                                            </div>
                                        </div>`;
                }
                if(select_val == 'html') {
                  var html = `
                    <div class="card" style="background-color: #cee5e9">
                        <div class="card-body">
                            <div class="acc_head">
                                  <div class="info">
                                    <span>Input Field:</span>&nbsp;&nbsp;
                                    <h4 style="display: inline-block; font-weight: 700; margin-bottom: 0"> ${select_text}</h4>
                                  </div>
                                  <div class="actions">
                                      <button type="submit" class="btn btn-sm btn-danger remove_input" onclick="return confirm('Do you really want to remove this form input?');"><i class="far fa-times-circle"></i></button>
                                  </div>
                                </div>
                                <form class="form_input_submit acc_body" action="${action}" method="POST" style="margin-top: 20px; display: none">
                                  ${csrf}
                                  ${method}
                                  <input type="hidden" name="type" value="${select_val}">
                                  <input type="hidden" name="form_group_id" value="${form_group_id}">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Field Label</label>
                                          <input type="text" class="form-control" name="label" required>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="form-group">
                                          <label>Content</label>
                                          <small style="display: block; line-height: 1.2; margin-bottom: 10px;">
                                            <strong>Instruction:</strong>
                                            This is not editable in forms and application status. Intendend for notes.
                                          </small>
                                          <textarea class="form-control summer_note form-input-back" name="settings"></textarea>
                                      </div>
                                    </div>
                                    <div class="col-md-1">
                                      <div class="form-group">
                                          <label>Sort</label>
                                          <input type="number" class="form-control" name="sort" value="0" min="0">
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                  </div>
                                </form>
                            </div>
                          </div>
                      </div>
                    `;
                } else {
                  var html = `
                      <div class="card" style="background-color: #cee5e9">
                          <div class="card-body">
                              <div class="acc_head">
                                <div class="info">
                                  <span>Input Field:</span>&nbsp;&nbsp;
                                  <h4 style="display: inline-block; font-weight: 700; margin-bottom: 0"> ${select_text}</h4>
                                </div>
                                <div class="actions">
                                    <button type="submit" class="btn btn-sm btn-danger remove_input" onclick="return confirm('Do you really want to remove this form input?');"><i class="far fa-times-circle"></i></button>
                                </div>
                              </div>
                                <form class="form_input_submit acc_body" action="${action}" method="POST" style="margin-top: 20px; display: none">
                                  ${csrf}
                                  ${method}
                                  <input type="hidden" name="type" value="${select_val}">
                                  <input type="hidden" name="form_group_id" value="${form_group_id}">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Field Label</label>
                                          <input type="text" class="form-control" name="label" required>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Field Description</label>
                                          <input type="text" class="form-control" name="description">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Field Placeholder</label>
                                          <input type="text" class="form-control" name="placeholder">
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label>Field CSS Classes</label>
                                          <input type="text" class="form-control" name="class">
                                      </div>
                                    </div>
                                    ${settings}
                                    <div class="col-md-1">
                                      <div class="form-group">
                                          <label>Sort</label>
                                          <input type="number" class="form-control" name="sort" value="0" min="0">
                                      </div>
                                    </div>
                                    <div class="col-md-5">
                                      <div class="form-group">
                                          <label>Is Required?</label>
                                          <div class="form-check">
                                              <input class="form-check-input" name="required" value="1" type="checkbox">
                                              <label class="form-check-label">Yes/No</label>
                                          </div>
                                      </div>
                                    </div>

                                    @if(\Request::is('applications/*'))
                                    <div class="row col-md-12">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <label for="restriction">Restriction</label>
                                              <select class="form-control" name="restriction" id="restriction">
                                                  <option value="" selected disabled>Please select a role</option>
                                                  @foreach($roles as $role)
                                                      <option value="{{ $role->name }}">{{ $role->label }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                      </div>
                                      <div class="col-md-8">
                                          <div class="form-group">
                                              <label>Application Status <small>(This is an automatic field message intended for application status)</small></label>
                                              <input type="text" class="form-control" name="application_status_message">
                                          </div>
                                      </div>
                                    </div>
                                    @endif

                                    <div class="col-md-12">
                                      <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                  </div>
                                </form>
                          </div>
                      </div>
                  `;
                }
              $('#field_inputs').show().prepend(html);
              $('.summer_note').summernote({
                height: 150,
                toolbar: [
                  [ 'style', [ 'style' ] ],
                  [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                  [ 'fontname', [ 'fontname' ] ],
                  [ 'fontsize', [ 'fontsize' ] ],
                  [ 'color', [ 'color' ] ],
                  [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                  [ 'table', [ 'table' ] ],
                  [ 'insert', [ 'link'] ],
                  [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
                ],
              });
          });

          $('body').on('click', '.acc_head', function(e){
              $(this).parent().find('.acc_body').slideToggle(100);
          });

          $('body').on('submit', '.form_input_submit', function(e){
            e.preventDefault();
            var action = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
              type: 'POST',
              url: action,
              data: data,
              success: function(res){
                if(res.success) {
                  location.reload();
                }
              }
            });
          });

          $('body').on('click', '.remove_input',function(){
            $(this).parents('.card').remove();
          });

      });
  </script>
@endif
</body>
</html>
