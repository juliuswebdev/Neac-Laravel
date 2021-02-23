<div class="form-group {{ $input->type }} @php echo ($input->class) ? $input->class : 'col-md-12'; @endphp">
                                      @if($input->type == 'html')
                                        {!! $input->settings !!}
                                      @else

                                      @php
                                        $required = ($input->required) ? 'required' : '';
                                      @endphp 

                                        <label>{{ $input->label }} <small>[ {{ $input->type }} ]</small>@if($required) <span style="color:red;">*</span> @endif</label>

                                        <div class="form-content">
                                          @if($input->type == 'text')

                                              <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" placeholder="{{ $input->placeholder }}" {{ $required }}>
                                          
                                          @elseif($input->type == 'text_area')
              
                                              <textarea name="{{ $input->type .'_fi_' .$input->id }}" class="form-control" rows="4" placeholder="{{ $input->placeholder }}" {{ $required }}>{{ $input->post }}</textarea>

                                          @elseif($input->type == 'email')

                                              <input type="email" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" placeholder="{{ $input->placeholder }}" {{ $required }}>

                                          @elseif($input->type == 'number')
                                              @php
                                                  $settings = array_pad(explode(':::',$input->settings),2,null);
                                                  $min = $settings[0];
                                                  $max = $settings[1];
                                              @endphp

                                              <input type="number" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" min="{{ $min }}" max="{{ $max }}" placeholder="{{ $input->placeholder }}" {{ $required }}>


                                          @elseif($input->type == 'url')

                                              <input type="url" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" placeholder="{{ $input->placeholder }}" {{ $required }}>

                                          <!-- CONTENT -->

                                          @elseif($input->type == 'summer_note')
                                              <small style="display: block; margin-bottom: 15px; ">{{ $input->placeholder }}</small>
                                              <textarea name="{{ $input->type .'_fi_' .$input->id }}" class="form-control summer_note" rows="4" placeholder="" {{ $required }}>{{ $input->post }}</textarea>

                                          @elseif($input->type == 'multiple_image')

                                              @php
                                                  $images = '';
                                                  if($input->post) {
                                                    $images = explode(',', $input->post);
                                                  }
                                              @endphp
                                              @if($images)
                                                <ul class="multiple_image_lists">
                                                @foreach($images as $image)
                                                    <li><img src="{{ $document_path }}{{ $image }}" alt=""></li>
                                                @endforeach
                                                </ul>
                                              @endif

                                              <div class="input-group">
                                                  <div class="custom-file">
                                                      <input type="file" name="{{ $input->type .'_fi_' .$input->id }}[]" value="{{ $input->post }}" class="custom-file-input" accept="{{ $input->settings }}" multiple>
                                                      <label class="custom-file-label" for="exampleInputFile">Choose Images</label>
                                                  </div>
                                              </div>
                                              <small>
                                                <strong>accepts: </strong>{{ $input->settings }}
                                              </small>

                                          @elseif($input->type == 'multiple_file')

                                              @php
                                                  $files = '';
                                                  if($input->post) {
                                                    $files = explode(',',$input->post);
                                                  }
                                              @endphp
                                              @if($files)
                                                <ul class="multiple_file_lists">
                                                @foreach($files as $file)
                                                    @php
                                                        $item = explode("_nurse_", $file);
                                                        $ext = explode(".", $file);
                                                    @endphp
                                                    <li><a  target="_blank" href="{{ $document_path }}{{ $file }}">{{ $item[0] }}.{{ $ext[1] }}</a></li>
                                                @endforeach
                                                </ul>
                                              @endif
                                              <div class="input-group">
                                                  <div class="custom-file">
                                                      <input type="file" name="{{ $input->type .'_fi_' .$input->id }}[]" value="{{ $input->post }}" class="custom-file-input" accept="{{ $input->settings }}" multiple>
                                                      <label class="custom-file-label" for="exampleInputFile">Choose Files</label>
                                                  </div>
                                              </div>
                                              <small>
                                                <strong>accepts: </strong>{{ $input->settings }}
                                              </small>


                                          <!-- SELECT -->

                                          @elseif($input->type == 'select')

                                              @php
                                                  $select = explode(':::', $input->settings);
                                              @endphp
                                              <select name="{{ $input->type .'_fi_' .$input->id }}" class="form-control">
                                              <option value="" selected disabled>{{ $input->placeholder }}</option>
                                              @foreach($select as $item)
                                                  @php
                                                      $select_item = array_pad(explode(' : ',$item),2,null);
                                                      $value = $select_item[0];
                                                      $text = $select_item[1];
                                                      $selected = '';
                                                      if($value == $input->post) {
                                                        $selected = 'selected';
                                                      }
                                                  @endphp
                                                  <option value="{{ $value }}" {{ $selected }}>{{ $text }}</option>
                                              @endforeach
                                              </select>

                                          @elseif($input->type == 'checkbox')

                                              @php
                                                  $select = explode(':::', $input->settings);
                                                  $count = 1;
                                              @endphp
                                              @foreach($select as $item)
                                              <div class="input-group">
                                                  @php
                                                      $select_item = array_pad(explode(' : ',$item),2,null);
                                                      $value = $select_item[0];
                                                      $text = $select_item[1];
                                                      $count++;
                                                      $checked = '';
                                                      $val_post = explode(',', $input->post);
                                                      if(in_array($value, $val_post)) {
                                                        $checked = 'checked';
                                                      }
                                                  @endphp
                                                  <div class="form-check">
                                                      <input class="form-check-input" name="{{ $input->type .'_fi_' .$input->id }}[]"  id="{{ $input->type .'_fi_' .$input->id. '_' .$count  }}" value="{{ $value }}" type="checkbox" {{ $checked }}>
                                                      <label for="{{ $input->type .'_fi_' .$input->id. '_' .$count }}" class="form-check-label">{{ $text }}</label>
                                                  </div>
                                              </div>
                                              @endforeach  

                                          @elseif($input->type == 'radio')

                                              @php
                                                  $select = explode(':::', $input->settings);
                                                  $count = 1;
                                              @endphp
                                              @foreach($select as $item)
                                              <div class="input-group">
                                                  @php
                                                      $select_item = array_pad(explode(' : ',$item),2,null);
                                                      $value = $select_item[0];
                                                      $text = $select_item[1];
                                                      $count++;
                                                      $checked = '';
                                                      if($value == $input->post) {
                                                        $checked = 'checked';
                                                      }
                                                  @endphp
                                                  <div class="custom-control custom-radio">
                                                      <input class="custom-control-input" name="{{ $input->type .'_fi_' .$input->id }}" id="{{ $input->type .'_fi_' .$input->id. '_' .$count  }}" value="{{ $value }}" type="radio" {{ $checked }}>
                                                      <label for="{{ $input->type .'_fi_' .$input->id. '_' .$count }}" class="custom-control-label">{{ $text }}</label>
                                                  </div>
                                              </div>
                                              @endforeach  

                                          
                                          
                                          <!-- Javascript -->


                                          @elseif($input->type == 'date_picker')

                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                  </div>
                                                  <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control input_blank date_picker_js" data-disable-previous="{{$input->settings}}" placeholder="{{ $input->placeholder }}" {{ $required }} autocomplete="off">
                                              </div>

                                          @elseif($input->type == 'time_picker')

                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                  </div>
                                                  <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control input_blank time_picker_js"  placeholder="{{ $input->placeholder }}" {{ $required }}>
                                              </div>

                                          @elseif($input->type == 'date_time_picker')

                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                  </div>
                                                  <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control input_blank date_time_picker_js" placeholder="{{ $input->placeholder }}" {{ $required }}>
                                              </div>
                                          
                                          @elseif($input->type == 'date_range_picker')

                                              <div class="input-group">
                                                  <div class="input-group-prepend">
                                                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                  </div>
                                                  <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control input_blank date_range_picker_js" placeholder="{{ $input->placeholder }}" {{ $required }}>
                                              </div>

                                          @elseif($input->type == 'color_picker')

                                            <div class="input-group color_picker_js colorpicker-element">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-square" style="color: {{ $input->post }};"></i></span>
                                                </div>
                                                <input type="text" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control"  placeholder="{{ $input->placeholder }}" {{ $required }}>
                                            </div>

                                          @endif
                                        </div>
                                      @endif
                                  </div>