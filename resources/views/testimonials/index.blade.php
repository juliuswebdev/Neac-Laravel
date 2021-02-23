<style>
    .category-area > div { display: none; }
    .category-area > div.active { display: block; }
    .btn i { line-height: 1.5; }
    form { margin-bottom: 0; }
    .deactivated td { text-decoration: none; background-color: #ffeff1 }
</style>
@extends('layouts.dashboard')

@section('name_content')
   Testimonials  <a href="javascript:void(0)"  data-toggle="modal" data-target="#addTestimonial" class="btn btn-success">Add Testimonial</a>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <!-- /.card-header -->
          <div class="card-body">
            <table id="table1"  class="table table-bordered table-hover">
              <thead>
                  <tr>
                      <th style="width: 10px;">#</th>
                      <th style="width: 200px">Category</th>
                      <th style="width: 200px;">Name</th>
                      <th style="width: 200px;">Subject</th>
                      <th>Description</th>
                      <th style="width: 50px;">Ratings</th>
                      <th style="width: 200px;">Actions</th>
                  </tr>
              </thead>  
              <tbody>
             @php
                $count = 1;
             @endphp
             @foreach($testimonials as $testimonial)                     
                  <tr class="@php echo ($testimonial->status == 1) ? 'active' : 'deactivated'; @endphp">
                      <td>{{ $count++ }}</td>
                      <td>
                        @if($testimonial->category == 0 || $testimonial->category == 1)
                          Successful Story
                        @elseif($testimonial->category == 2)
                          Licensed Applicants
                        @elseif($testimonial->category == 3)
                          Video Testimonials
                        @endif
                      </td>
                      <td>
                        @if($testimonial->user)
                          {{ $testimonial->user->first_name }} {{ $testimonial->user->last_name }}<small><br>{{ $testimonial->user->email }}</small>
                        @else
                          {{ $testimonial->applicant_name }}
                        @endif
                      </td>
                      <td><div style="max-height: 100px; overflow: hidden;">{{ $testimonial->subject }}</div></td>
                      <td><div style="max-height: 100px; overflow: hidden;">{{ $testimonial->description }}</div></td>
                      <td><div style="max-height: 100px; overflow: hidden;">{{ $testimonial->rating }}</div></td>
                      <td>
                        @can('testimonial-show')
                          <a onclick="open_window('{{ route('testimonials.show',$testimonial->id) }}')" href="javascript:void(0)" class="btn btn-default" title="View"><i class="fas fa-eye"></i></a>
                        @endcan

                        @can('testimonial-delete')
                        <form action="{{ route('testimonials.destroy',$testimonial->id) }}" method="POST" style="display: inline-block">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Do you really want to delete this testimonial?');" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                        @endcan
                        
                      </td>
                  </tr>
              @endforeach 
                </tbody>
            </table>
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
  <div class="modal fade" id="addTestimonial" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Add Testimonial</small></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">



              <div class="form-group">
                <label for="">Category</label>
                <select name="category" class="form-control">
                  <option disabled selected>Please Choose</option>
                  <option value="1">Successful Story</option>
                  <option value="2">Licensed Applicants</option>
                  <option value="3">Video Testimonials</option>
                </select>
              </div>
              <div class="category-area">
                <div>
                  <div class="form-group">
                    <label for="">Subject</label>
                    <input type="text" name="subject" class="form-control" placeholder="HAAD Passer">
                  </div>
                  <div class="form-group">
                    <label for="">Applicant Fullname</label>
                    <input type="text" name="applicant_name" class="form-control" placeholder="Juan Dela Cruz">
                  </div>
                  <div class="form-group">
                    <label for="uploadImage">Upload Applicant Picture Here</label>
                    <div class="form-content" style="margin-bottom: 5px;">
                      <div class="input-group">
                        <div class="custom-file" style="margin-bottom: 10px;">
                          <input type="file" class="form-control-file custom-file-input" id="uploadImage" name="applicant_image" accept=".jpg, .gif, .png">
                          <label class="custom-file-label" for="uploadImage">Choose File</label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                  </div>
                </div>
                <div>
                  <div class="form-group">
                    <label for="uploadImage">Upload your Image here</label>
                    <div class="form-content" style="margin-bottom: 5px;">
                      <div class="input-group">
                        <div class="custom-file" style="margin-bottom: 10px;">
                          <input type="file" class="form-control-file custom-file-input" id="uploadImage" name="image" accept=".jpg, .gif, .png">
                          <label class="custom-file-label" for="uploadImage">Choose File</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div>
                  <div class="form-group">
                    <label for="">Youtube Video</label>
                    <input type="text" name="video" class="form-control" placeholder="Ex. <iframe></iframe>">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="">Rating</label>
                <input type="number" max="5" min="0" value="5" name="rating" class="form-control">
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
      </form>
    </div>
  </div>

@endsection