<style>
    .acc_head:hover { cursor: pointer; }
    .info, .actions { float: left }
    .info { width: calc(103% - 100px) }
    .actions { margin-top: 2px }
    .round_count { background-color: #ccc;
    width: 35px;
    height: 35px;
    border-radius: 100%;
    display: inline-block;
    text-align: center;
    color: #fff;
    line-height: 2; }

    h1 { margin-top: 2 0px!important; }
    footer.main-footer,
    nav, aside, .breadcrumb { display: none!important }
    body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
@extends('layouts.dashboard')

@section('name_content')
    Show Form
@endsection

@section('content')

<!-- Main content -->

<section class="content">

    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-info">
            <!-- /.card-header -->
                <div class="card-header">
                    <h2 class="card-title">Form</h2>
                </div>
                <div class="card-body">
                    <h2>{{ $forms->name }}</h2>
                    @if($forms->description)
                    <p>{{ $forms->description }}</p>
                    @endif
                </div>
            </div>
            @if($forms->inputs->count() == 0) 
            <div style="display: none;" id="field_inputs">
            </div>
            @else
            <div id="field_inputs">
                @php $count = 1; @endphp
                @foreach($forms->inputs as $item)
                <div class="card">
                    <div class="card-body" >
                        <div class="acc_head mb-3">
                            <div class="info">
                                <span class="round_count">{{ $count++ }}</span>&nbsp;&nbsp;
                                <h4 style="display: inline-block; font-weight: 700; margin-bottom: 0"> {{ ucfirst($item->label) }}</h4>
                                &nbsp;&nbsp;[ {{ $item->type }} ] &nbsp;&nbsp;<small>Sort : {{ $item->sort }}</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                                <label>Field Label</label>
                                <input type="text" class="form-control" name="label" value="{{ $item->label }}" required>
                            </div>
                            </div>

                            @if($item->type == 'html')

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Content</label>
                                        <small style="display: block; line-height: 1.2; margin-bottom: 10px;">
                                        <strong>Instruction:</strong>
                                        This is not editable in forms and application status. Intendend for notes.
                                        </small>
                                        <textarea class="form-control summer_note" name="settings">{{ $item->settings }}</textarea>
                                    </div>
                                </div>

                            @else

                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>Field Description</label>
                                    <input type="text" class="form-control" name="description" value="{{ $item->description }}">
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>Field Placeholder</label>
                                    <input type="text" class="form-control" name="placeholder" value="{{ $item->placeholder }}">
                                </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                    <label>Field CSS Classes</label>
                                    <input type="text" class="form-control" name="class" value="{{ $item->class }}">
                                </div>
                                </div>

                            @endif

                            @if($item->type == 'select' || $item->type == 'checkbox' || $item->type == 'radio')
                                @php
                                    $settings = explode(':::', $item->settings);
                                    $value = implode("\n", $settings);
                                @endphp
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Items</label>
                                                <small  style="display: block; line-height: 1.2; margin-bottom: 10px;">
                                                <strong>Instruction:</strong>
                                                Enter each choice on a new line.<br>
                                                For more control, you may specify both a value and label like this:<br>
                                                red : Red<br>
                                                </small>
                                                <textarea type="text" class="form-control" name="settings" style="min-height: 200px; max-height: 600px;">{!! html_entity_decode($value) !!}</textarea>
                                            </div>
                                        </div>
                            
                            @elseif($item->type == 'number') 
                                @php
                                    $settings = array_pad(explode(':::',$item->settings),2,null);
                                    $min = $settings[0];
                                    $max = $settings[1];
                                @endphp
                                        <div class="col-md-6">
                                            <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label>Minimum</label>
                                                <input type="number" class="form-control" name="minimum" value="{{ $min }}" required>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>Maximum</label>
                                                <input type="number" class="form-control" name="maximum" value="{{ $max }}" required>
                                            </div>
                                            </div>
                                        </div>
                            
                            @elseif($item->type == 'multiple_file' || $item->type == 'multiple_image')

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Items</label><br>
                                                <small  style="display: block; line-height: 1.2; margin-bottom: 10px;">
                                                    <strong>Instruction:</strong>
                                                    Enter each choice on a new line.<br>
                                                    For more control, you may specify accept file extension like this:<br>
                                                    .jpg, .docx, .txt<br>
                                                </small>
                                                <textarea type="text" class="form-control" name="settings" value="{{ $item->settings }}" style="min-height: 200px; max-height: 600px;">{{ $item->settings }}</textarea>
                                            </div>
                                        </div>

                            @endif

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Sort</label>
                                    <input type="number" class="form-control" name="sort" value="{{ $item->sort }}"  min="0">
                                </div>
                            </div>

                            @if($item->type != 'html')

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label>Is Required?</label>
                                        <div class="form-check">
                                            <input class="form-check-input" name="required" value="1" type="checkbox" @if($item->required) checked @endif>
                                            <label class="form-check-label">Yes/No</label>
                                        </div>
                                    </div>
                                </div>

                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

@endsection

