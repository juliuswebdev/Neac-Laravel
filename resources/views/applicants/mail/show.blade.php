<style>
  h1 { margin-top: 2 0px!important; }
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
@extends('layouts.dashboard')
@section('content')

  <!-- Main content -->
  @php
    $document_path = '/documents/';
  @endphp
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-info">
        <!-- /.card-header -->
            <div class="card-header">
                <h3 class="card-title">Mail to [{{ $mail->to }}]</h3>
            </div>
            <div class="card-body">

                    <div class="form-group">
                        <label>To:  </label>
                        {{ $mail->to }}
                    </div>
                    <div class="form-group">
                        <label>CC:  </label>
                        {{ $mail->cc }}
                    </div>
                    <div class="form-group">
                        <label>BCC:  </label>
                        {{ $mail->bcc }}
                    </div>
                    <div class="form-group">
                        <label>Subject:  </label>
                        {{ $mail->subject }}
                    </div>
                    <div class="form-group">
                        <label>Attachment/s</label>
                        <br>
                        @if($mail->attachments)
                            @php
                                $attachments = explode(',',$mail->attachments);
                            @endphp
                            @foreach($attachments as $attachment)
                                @php
                                    $item = explode("_nurse_", $attachment);
                                    $ext = explode(".", $attachment);
                                @endphp
                                <a href="{{ $document_path }}{{ $attachment }}" download>{{ $item[0] }}.{{ $ext[1] }}</a><br>
                            @endforeach
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Messages:  </label><br>
                        {!! $mail->messages !!}
                    </div>

            </div>
        </div>
      </div>
  </section>
  <!-- /.content -->

@endsection