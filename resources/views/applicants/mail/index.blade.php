<style>
  h1 { margin-top: 2 0px!important; }
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
@extends('layouts.dashboard')

@section('name_content')
    Send Mail
@endsection
@section('content')
  @php
    $document_path = '/documents/';
  @endphp
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-info">
        <!-- /.card-header -->
            <div class="card-header">
                <h3 class="card-title">Send Mail to [{{ $user->email }}]</h3>
            </div>
            <div class="card-body">
                @if(session()->has('message'))
                    <div class="alert alert-{{ session()->get('alert') }} alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <form action="{{ route('mail.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="to" value="{{ $user->email }}">
                    <div class="form-group">
                        <label>To<sup>*</sup></label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>CC</label>
                        <input type="email" name="cc" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>BCC</label>
                        <input type="email" name="bcc" class="form-control" value="">
                    </div>
                    <div class="form-group">
                        <label>Subject<sup>*</sup></label>
                        <input type="text" name="subject" class="form-control" value="" required>
                    </div>
                    <div class="form-group">
                        <label>Attachment/s</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" name="attachments[]" class="custom-file-input" multiple="multiple">
                                <label class="custom-file-label" for="exampleInputFile">Choose Files</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Messages<sup>*</sup></label>
                        <textarea name="messages" class="form-control summer_note" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Mail Archive</h3>
            </div>
            <div class="card-body">
                @include('commons.mail-table', $mails)
            </div>
        </div>
      </div>
  </section>
  <!-- /.content -->
@endsection