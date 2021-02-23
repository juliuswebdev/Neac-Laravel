@extends('layouts.dashboard')

@section('name_content')
    Add Service
@endsection

@section('content')
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
            
            <form action="{{ route('services.store') }}" method="POST">
              @csrf
              <div class="row">
                <div class="col-md-7">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="name">Name</label>
                        <div class="input-group">
                          <input type="text" class="form-control" name="name" id="name">
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
                          <input type="text" class="form-control" name="price" id="price" autocomplete="off">
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="sort">Sort</label>
                        <div class="input-group">
                          <input type="number" class="form-control" name="sort" id="sort" autocomplete="off">
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="category_id">Select Category</label>
                        <select class="form-control" id="category_id"  name="category_id">
                          @foreach($service_category as $cat)
                          <option value="{{ $cat->category_id }}">{{ $cat->name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type">
                          <optgroup label="Type :::">
                            <option value="ANY">ANY</option>
                            <option value="NEW">NEW</option>
                            <option value="OLD">OLD</option>
                          </optgroup>
                          <optgroup label="Online Review">
                              <option value="IELTS">IELTS</option>
                              <option value="NCLEX">NCLEX</option>
                              <option value="HAAD">HAAD</option>
                              <option value="Saudi">Saudi</option>
                              <option value="DHA">DHA</option>
                              <option value="MOH">MOH</option>
                              <option value="NMBI">NMBI</option>
                              <option value="NMC-UK">NMC-UK</option>
                          </optgroup>
                          <optgroup label="Other Services">
                              <option value="English Exams">English Exams</option>
                              <option value="Training &amp; Certification">Training &amp; Certification</option>
                              <option value="PRC">PRC</option>
                              <option value="School Processing">School Processing</option>
                          </optgroup>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-5">
                  <div class="form-group">
                    <label for="name">State</label>
                    <div class="input-group">
                      <textarea name="state" class="form-control" style="min-height: 210px;"></textarea>
                    </div>
                  </div>
                </div>
                <div class="col-12">           
                  <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control summer_note" name="description" id="description"></textarea>
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
    </div>
  </section>
@endsection