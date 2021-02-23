<div class="form-group">
    @if($input->type == 'html')
    {!! $input->settings !!}
    @else
    @php
        $required = ($input->required) ? 'required' : '';
    @endphp 
    <label>{{ $input->label }} @if($required)<span style="color:red;">*</span>@endif</label>

    <div class="form-content">
        @if($input->post)
            @if($input->type == 'text')

                {{ $input->post }}

            @elseif($input->type == 'text_area')

                {!! $input->post !!}

            @elseif($input->type == 'email')

                <input type="email" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" placeholder="{{ $input->placeholder }}" {{ $required }}>

            @elseif($input->type == 'number')

                {{ $input->post }}

            @elseif($input->type == 'url')

                <input type="url" name="{{ $input->type .'_fi_' .$input->id }}" value="{{ $input->post }}" class="form-control" placeholder="{{ $input->placeholder }}" {{ $required }}>

            <!-- CONTENT -->

            @elseif($input->type == 'summer_note')

                {!! $input->post !!}

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
                

            <!-- SELECT -->

            @elseif($input->type == 'select')

                @php
                    $select = explode(':::', $input->settings);
                @endphp

                @foreach($select as $item)
                    @php
                        $select_item = array_pad(explode(' : ',$item),2,null);
                        $value = $select_item[0];
                        $text = $select_item[1];
                        
                        if($value == $input->post) {
                        echo $text;
                        }
                    @endphp
            
                @endforeach


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
                    
                        $val_post = explode(',', $input->post);
                        if(in_array($value, $val_post)) {
                        echo $text;
                        }
                    @endphp
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
                        echo $text;
                        }
                    @endphp
                    
                </div>
                @endforeach  
            <!-- Javascript -->
            @elseif($input->type == 'date_picker')

                {{ $input->post }}

            @elseif($input->type == 'time_picker')

                {{ $input->post }}

            @elseif($input->type == 'date_time_picker')

                {{ $input->post }}
            
            @elseif($input->type == 'date_range_picker')

                {{ $input->post }}

            @elseif($input->type == 'color_picker')

                <i class="fas fa-square" style="color: {{ $input->post }};"></i>

            @endif
        @else
            ---
        @endif
    </div>
    @endif
</div>