$(document).ready(function(){

      $('.nav-tabs-neac li a').click(function(e){
        e.preventDefault();
        var href = $(this).attr('href');
        var href_class = '.'+ href.substring(1);
        var parent = $(this).parents('.transaction-content');
        parent.find('.all').hide();
        parent.find(href_class).show();
        if( href_class == '.all' ) {
            parent.find('.tab-content > div').show();
        }
        parent.find('.tab-no-content').remove();
        if(parent.find(href_class).length == 0) {
            parent.find('.tab-content').append('<p class="tab-no-content bg-white p-3">No item found!</p>') 
        }
    });

    // Summernote
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
      callbacks: {
        onPaste: function (e) {
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            document.execCommand('insertText', false, bufferText);
        }
      }
    });

    $('.summer_note').each(function(){
      var val = $(this).val();
      $(this).summernote('code',val);
    });

  


    $('.date_picker_js').each(function(){
      var dateToday = false;
      if( $(this).data('disable-previous') == 1 ) {
        dateToday = new Date();
      }
      $(this).daterangepicker({
          format: 'LT',
          singleDatePicker: true,
          showDropdowns: true,
          autoUpdateInput: false,
          minDate: dateToday,
          locale: {
            format : 'MM/DD/YYYY'
          }
      });
    });


    $('.date_picker_js').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('.time_picker_js').daterangepicker({
        format: 'LT',
        timePicker : true,
        singleDatePicker:true,
        timePicker24Hour : true,
        timePickerIncrement : 30,
        timePickerSeconds : true,
        autoUpdateInput: false,
        locale : {
            format : 'HH:mm:ss A'
        }
    }).on('show.daterangepicker', function(ev, picker) {
            picker.container.find(".calendar-table").hide();
    });
    $('.time_picker_js').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('HH:mm:ss A'));
    });

    $('.date_time_picker_js').daterangepicker({
        format: 'LT',
        timePicker : true,
        singleDatePicker:true,
        timePicker24Hour : true,
        timePickerIncrement : true,
        timePickerSeconds : true,
        autoUpdateInput: false,
        locale: {
          format: 'MM/DD/YYYY HH:mm:ss A'
        },
    });
    $('.date_time_picker_js').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY HH:mm:ss A'));
    });
    $("body").delegate(".payed_at", "focusin", function(){
        $(this).daterangepicker({
          format: 'LT',
          timePicker : true,
          singleDatePicker:true,
          timePicker24Hour : true,
          timePickerIncrement : true,
          timePickerSeconds : true,
          locale: {
            format: 'YYYY-MM-DD HH:mm:ss'
          },
        });
    });
    $('.payed_at').daterangepicker({
        format: 'LT',
        timePicker : true,
        singleDatePicker:true,
        timePicker24Hour : true,
        timePickerIncrement : true,
        timePickerSeconds : true,
        locale: {
          format: 'YYYY-MM-DD HH:mm:ss'
        },
    });
    $('.payed_at').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
    });

    $('.date_range_picker_js').daterangepicker({
      format: 'LT',
      autoUpdateInput: false,
    });
    $('.date_range_picker_js').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('.date_range_picker_js2').daterangepicker({
      format: 'LT',
      autoUpdateInput: false,
    });
    $('.date_range_picker_js2').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + '::' + picker.endDate.format('YYYY-MM-DD'));
    });

    $('.color_picker_js').colorpicker();
    $('.color_picker_js').on('colorpickerChange', function(event) {
      $(this).find('.fa-square').css('color', event.color.toString());
      $(this).val(event.color.toString());
    });

    $('input[type="file"]').change(function(e){
            var fileName = '';
            for(var x = 0; x < e.target.files.length; x++) {
              fileName += '<small style="display: block;">'+ e.target.files[x].name +'</small>';
            }
            // alert('The file "' + fileName +  '" has been selected.');
            $(this).parents('.form-group').append(fileName);
            $(this).parent().css('margin-bottom', '10px');
    });

    $('#birth_date').daterangepicker({
        format: 'LT',
        singleDatePicker: true,
        showDropdowns: true,
        autoUpdateInput: false,
        locale: {
          format : 'MM/DD/YYYY'
        }
    });
    $('#birth_date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $('.generate-password').click(function(e){
      e.preventDefault();
      var elem = $(this).parents('form').find('.password');
      elem.val(Math.random().toString(36).slice(-8));
      elem.attr('type', 'text');
    });

    $('.show-hide-password').click(function(e){
      e.preventDefault();
      var elem = $(this).parents('form').find('.password'),
          type  = elem.attr('type');
          
      if(type == 'password') {
        elem.attr('type', 'text');
        $(this).html('<i class="fas fa-eye"></i>');
      } else {
        elem.attr('type', 'password');
        $(this).html('<i class="fas fa-eye-slash"></i>');
      }
    });

    $('.change-password').submit(function(e){
        e.preventDefault();
        var data = $(this).serialize(),
            action = $(this).attr('action');
        $(this).find('button.btn').prop('disabled', true).append('<i class="fas ml-1 fa-spinner fa-spin"></i>');
        $(this).find('input').prop('disabled', true);
        $.ajax({
          type: 'POST',
          url: action,
          data : data,
          context: this,
          success: function(data) {

            var html = `
              <div class="alert alert-${data.alert}" style="margin: 10px 0 0;">${data.message}</div>
            `;
            $(this).find('.alert-change-password').html(html);
            $(this).find('button.btn').prop('disabled', false);
            $(this).find('input').prop('disabled', false);
            $(this).find('button.btn i.fas').remove();
          }
        });
    });

    $('.resetPassword-modal').on('hidden.bs.modal', function () {
      $(this).find('input').val('');
      $(this).find('div.alert').remove();
		});

    $(document).ready(function(){
      $('select[name="category"]').change(function(){
        var index = $(this).val();
        if(index == 0 || index == 1) {
          $('.category-area > div').eq(0).addClass('active').siblings().removeClass('active');
        } else {
          $('.category-area > div').eq(index - 1).addClass('active').siblings().removeClass('active');
        }
      });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('.profile-image-upload').html('<img src="'+e.target.result+'"><span>Change Photo</span>');
                $('.profile-image-upload').css({
                    'background': 'transparent',
                    'padding': '3px'
                });
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#profile-image").change(function() {
        readURL(this);
    });

    $('.select2').select2();

    $("#table1").DataTable({
      "responsive": true,
      "autoWidth": true,
      "paging": true,
      "lengthChange": true,
      "searching":true,
      "ordering": true,
      "info": true,
    });
  
    $('#table2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#mail-table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('#service-table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });

    $('.input-permission').click(function(){
        var role = $(this).data('role-id'),
            permission = $(this).data('permission-id');
        const data = {
          'role' : role,
          'permission' : permission,
          'action' : 1,
        };
        if ($(this).is(':checked')) {
          data.action = 1;
        } else {
          data.action = 0;
        }
        console.log(data);
        $.ajax({
            type: 'POST',
            url: '/roles-permissions/assign-permissionto-role',
            data: data,
            success: function(res){
              alert(res.success);
            }
        });
    });

    $('.find-user').submit(function(e){
        e.preventDefault();
        var data = $(this).serialize(),
            action = $(this).attr('action');
            $(this).find('button.btn').prop('disabled', true).append('<i class="fas ml-1 fa-spinner fa-spin"></i>');
        $(this).find('input').prop('disabled', true);
        $.ajax({
          type: 'POST',
          url: action,
          data : data,
          context: this,
          success: function(data) {
            if(data.alert == 'success') {
              var html = `
                <h5 style="border-bottom: 1px solid #dee2e6;display: block;padding-bottom: 7px;"><strong>User Information</strong></h5>
                <div class="row">
                  <div class="form-group col-md-4">
                    <label>First Name: </label><br>${data.user.first_name}
                  </div>
                  <div class="form-group col-md-4">
                    <label>Middle Name: </label><br>${ (data.user.middle_name) ? data.user.middle_name : 'N/A' }
                  </div>
                  <div class="form-group col-md-4">
                    <label>Last Name: </label><br>${data.user.last_name}
                  </div>
                  <div class="form-group col-md-8">
                    <label>Email: </label><br>${data.user.email}
                  </div>
                  <div class="form-group col-md-4">
                    <label>Status: </label><br>${ (data.user.approval && data.user.status) ? 'Active' : 'Pending/Cancelled' }
                  </div>
                  <div class="col-md-12">
                    <label>Reseller Code Used: </label>${ (data.user.reseller_code_used) ? data.user.reseller_code_used : 'N/A' }
                  </div>
                </div>
              `;
              $('#services_info, #transaction_history, #user_info, #payment_form').css('display', 'block');
              $('#transaction_history #user_id').val(data.user.id);
              $('#place_order').find('div.alert').parent().remove();
              $('#user_info').html(html);

            } else {
              var html = `
                <div class="col-md-12">
                  <div class="alert alert-${data.alert}" style="margin: 10px 0 0;">${data.message}</div>
                </div>
              `;
              $('#place_order').append(html);
              $('#services_info, #transaction_history, #user_info, #payment_form').css('display', 'none');
            }
            
            $(this).find('button.btn').prop('disabled', false);
            $(this).find('input').prop('disabled', false);
            $(this).find('button.btn i.fas').remove();
          }
        });
    })

    $('.lock-function a').click(function(e){
        var id = $(this).data('applicant_id'),
            token = $(this).data('token');
        $.ajax({
            type: 'POST',
            url: '/applicants/'+id+'/lock',
            context: this,
            data: {
              "_token": token,
              "id": id
            },
            success: function(res){
              if(res.status == 1) {
                $(this).html('<i class="fas fa-lock"></i><span class="ml-1">'+res.user+'</span>');
              } else {
                $(this).html('<i class="fas fa-unlock"></i><span class="ml-1">Unlock</span>');
              }
            }
        });
    });

    $('.activate-function a').click(function(e){
      var id = $(this).data('applicant_id'),
          token = $(this).data('token');
      $.ajax({
          type: 'POST',
          url: '/applicants/'+id+'/approve',
          context: this,
          data: {
            "_token": token,
            "id": id
          },
          success: function(res){
            if(res.status == 1) {
              window.location.reload();
            }
          }
      });
  });

    $('.input-disabled-edit').click(function(e){
      e.preventDefault();
      $('.input-disabled').prop('disabled', false);
      $('.input-disabled-update').show();
      $('.input-disabled').removeClass('input-disabled');
      $(this).hide();
      return false;
    })

    $('.reseller-update').click(function(e){
      e.preventDefault();
            var id = $(this).data('user_id'),
            token = $(this).data('token'),
            reseller_code = $('.reseller-input').val();
      $.ajax({
        type: 'POST',
        url: '/reseller/'+id+'/update-code',
        context: this,
        data: {
          "_token": token,
          "id": id,
          "reseller_code": reseller_code
        },
        success: function(res){
          if(res.alert == 'warning') {
            alert(res.message);
          } else if(res.alert == 'success'){
            window.location.href= "/reseller?code="+reseller_code
          }
        }
      });
    });

    $('.email-update').click(function(e){
      e.preventDefault();
            var id = $(this).data('user_id'),
            token = $(this).data('token'),
            email = $('.email-input').val();
      $.ajax({
        type: 'POST',
        url: '/profile/'+id+'/update-email',
        context: this,
        data: {
          "_token": token,
          id,
          email
        },
        success: function(res){
            alert(res.message);
            if(res.alert == 'success') {
              $('.email-input').prop('disabled', true);
              $('.input-disabled-edit').show();
              $('.input-disabled-update').hide();
              $('.email-input').addClass('input-disabled');
            }
        }
      });
    });

    

    $('#ref').change(function(){
      $('#ref_val').prop("disabled", false);
    });

  });
  function open_window(url) {
    window.open(url,'_blank','width=1000,height=600,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,scrollbars=yes,status=no');
  }
