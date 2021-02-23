@extends('layouts.dashboard')

@section('name_content')
    Edit {{ $service->name }} [ {{ $service->service_category['name']  }} ]
@endsection

@section('content')
<style>
  h1 { margin-top: 2 0px!important; }
  footer.main-footer,
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-8">

        @if(session()->has('message'))
          <div class="col-md-12">
            <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                {{ session()->get('message') }}
            </div>
          </div>
        @endif

        <div class="card">
          
          <!-- /.card-header -->
          <div class="card-body">
            
            <form action="{{ route('services.update', $service->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="row">
                <div class="col-md-7">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="name">Name</label>
                        <div class="input-group">
                          <input type="text" class="form-control" name="name" id="name" value="{{ $service->name }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="price">Price</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <div class="input-group-text">USD</div>
                          </div>
                          <input type="text" class="form-control" name="price" id="price" autocomplete="off" value="{{ $service->price }}">
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="sort">Sort</label>
                        <div class="input-group">
                          <input type="number" class="form-control" name="sort" id="sort" autocomplete="off" value="{{ $service->sort }}">
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-6">
                      <div class="form-group">
                        <label for="category_id">Select Category</label>
                        <select class="form-control" id="category_id"  name="category_id">
                          @foreach($service_category as $cat)
                            @php $selected = ($cat->category_id == $service->category_id) ? 'selected' : ''; @endphp
                            <option value="{{ $cat->category_id }}" {{ $selected }}>{{ $cat->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                          <optgroup label="Type :::">
                            <option value="ANY" @php echo ($service->type == 'ANY') ? 'selected' : ''; @endphp>ANY</option>
                            <option value="NEW" @php echo ($service->type == 'NEW') ? 'selected' : ''; @endphp>NEW</option>
                            <option value="OLD" @php echo ($service->type == 'OLD') ? 'selected' : ''; @endphp>OLD</option>
                          </optgroup>
                          <optgroup label="Online Review">
                              <option value="IELTS" @php echo ($service->type == 'IELTS') ? 'selected' : ''; @endphp>IELTS</option>
                              <option value="NCLEX" @php echo ($service->type == 'NCLEX') ? 'selected' : ''; @endphp>NCLEX</option>
                              <option value="HAAD" @php echo ($service->type == 'HAAD') ? 'selected' : ''; @endphp>HAAD</option>
                              <option value="Saudi" @php echo ($service->type == 'Saudi') ? 'selected' : ''; @endphp>Saudi</option>
                              <option value="DHA" @php echo ($service->type == 'DHA') ? 'selected' : ''; @endphp>DHA</option>
                              <option value="MOH" @php echo ($service->type == 'MOH') ? 'selected' : ''; @endphp>MOH</option>
                              <option value="NMBI" @php echo ($service->type == 'NMBI') ? 'selected' : ''; @endphp>NMBI</option>
                              <option value="NMC-UK" @php echo ($service->type == 'NMC-UK') ? 'selected' : ''; @endphp>NMC-UK</option>
                          </optgroup>
                          <optgroup label="Other Services">
                              <option value="English Exams" @php echo ($service->type == 'English Exams') ? 'selected' : ''; @endphp>English Exams</option>
                              <option value="Training Certification" @php echo ($service->type == 'Training Certification') ? 'selected' : ''; @endphp>Training &amp; Certification</option>
                              <option value="PRC" @php echo ($service->type == 'PRC') ? 'selected' : ''; @endphp>PRC</option>
                              <option value="School Processing" @php echo ($service->type == 'School Processing') ? 'selected' : ''; @endphp>School Processing</option>
                          </optgroup>
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <input type="checkbox" name="tax" id="tax" value="1" @if($service->tax) checked @endif>
                        <label for="tax">Enable Tax</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-5">
                  <div class="form-group">
                    <label for="name">State</label>
                    <div class="input-group">
                      <textarea name="state" class="form-control" style="min-height: 210px;">{!! $service->state !!}</textarea>
                    </div>
                  </div>
                </div>
                <div class="col-12">           
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control summer_note" name="description" id="description">{!! $service->description !!}</textarea>
                  </div>
                </div>
              </div>
              <div class="footer">
                <button type="submit" class="btn btn-primary">SUBMIT</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      @if($service->state)
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            @php $states = explode("\r\n", $service->state); @endphp
            @foreach($states as $state)
              {{ $state }}<br>
            @endforeach
          </div>
        </div>
      </div>
      @endif
    </div>
  </section>

@endsection