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
        <div class="card card-outline 
        @php
        if($testimonial->status == 0) {
            echo 'card-danger';
        } elseif($testimonial->status == 1) {
            echo 'card-success';
        }
        @endphp
        ">
        <!-- /.card-header -->
            <div class="card-header">
                <h3 class="card-title"> 
                    @if($testimonial->category == 0 || $testimonial->category == 1)
                        Successful Story
                    @elseif($testimonial->category == 2)
                        Licensed Applicants
                    @elseif($testimonial->category == 3)
                        Video Testimonials
                    @endif
                </h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="">Applicant Name: </label>
                    @if($testimonial->user)
                        {{ $testimonial->user->first_name }} {{ $testimonial->user->last_name }}<small><br>{{ $testimonial->user->email }}</small>
                    @elseif($testimonial->applicant_name)
                        {{ $testimonial->applicant_name }}
                    @else
                        Admin
                    @endif
                </div>
                @if($testimonial->applicant_image)
                    <div class="form-group">
                        <label for="">Applicant Image: </label> <br>
                        <img src="/documents/{{ $testimonial->applicant_image }}" alt="">
                    </div>
                @endif

                @if($testimonial->category == 0 || $testimonial->category == 1)
                    <div class="form-group">
                        <label>Subject:  </label>
                        {{ $testimonial->subject }}
                    </div>

                    <div class="form-group">
                        <label>Description:  </label>
                        {{ $testimonial->description }}
                    </div>
                @endif

                @if($testimonial->category == 2)
                    <div class="form-group">
                        <label>Image:  </label>
                        <img src="/documents/{{ $testimonial->image }}" alt="">
                    </div>
                @endif

                @if($testimonial->category == 3)
                    <div class="form-group">
                        <label>Video:  </label>
                        {!! $testimonial->video !!}
                    </div>
                @endif

                <div class="form-group">
                    <label>Rating:  </label>
                    {{ $testimonial->rating }}
                </div>

                @if($testimonial->user)
                    <div class="form-group">
                        <label>URL:  </label>
                        {{ $testimonial->url }}
                    </div>

                    <div class="form-group">
                        <label>Attachment/s</label>
                        <br>
                        @if($testimonial->attachments)
                            @php
                                $attachments = explode(',',$testimonial->attachments);
                            @endphp
                            @foreach($attachments as $attachment)
                                @php
                                    $item = explode("_nurse_", $attachment);
                                    $ext = explode(".", $attachment);
                                @endphp
                                <a href="{{ $document_path }}{{ $attachment }}">{{ $item[0] }}.{{ $ext[1] }}</a><br>
                            @endforeach
                        @endif
                    </div>

                @endif

                <div class="form-group">
                    @can('testimonial-disable-enable')
                        @if( $testimonial->status == 1 )
                        <form action="{{ route('testimonials.deactivate',$testimonial->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('post')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to deactivate this testimonial?');" title="Deactivate"><i class="fas fa-ban"></i></button>
                        </form>
                        @else
                        <form action="{{ route('testimonials.activate',$testimonial->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('post')
                            <button type="submit" class="btn btn-success" onclick="return confirm('Do you really want to activate this testimonial?');" title="Activate"><i class="fas fa-check"></i></button>
                        </form>
                        @endif
                    @endcan

                    @can('testimonial-delete')
                    <form action="{{ route('testimonials.destroy',$testimonial->id) }}" method="POST" style="display: inline-block">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to delete this testimonial?');" title="Activate"><i class="fas fa-trash"></i></button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
      </div>
  </section>
  <!-- /.content -->

@endsection