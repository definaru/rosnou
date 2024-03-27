$( function(){

    // Open file upload dialog
    $(document).on('click', '.ch-cropper-block', function(){

        var id = $(this).data('id');
        var $file = $('.ch-cropper-file[data-id="'+id+'"]');

        $file.click();
    });

});


$( function(){

    // File upload process
    $(document).on('change', 'input.ch-cropper-file', function(e){

        var $input = $(this);
        var id = $input.data('id');
        var $block = $('.ch-cropper-block[data-id="'+id+'"]');
        var $popup = $('.ch-cropper-popup[data-id="'+id+'"]');

        var formData = new FormData();

        formData.append('ch-cropper-tmp-ajax', 1);
        formData.append('ch-cropper-file', $input[0].files[0]);

        // clean file input to trigger change where select
        // same file again
        $input.val('');

        $.ajax({
               url : '',
               type : 'POST',
               data : formData,
               processData: false,  // tell jQuery not to process the data
               contentType: false,  // tell jQuery not to set contentType
               success : function(data) {

                  $popup.find('.img-container').css('visibility', 'hidden');
                  $popup.modal('show');

                  var cropUrl = '';

                  var callback = function(src){

                      $block.find('img').attr('src', src);
                      $block.addClass('profile-avatar-active');

                      //$('img.user_avatar').attr('src', src);

                      return;
                  }

                  setTimeout( function(){
                      initCropper($popup, cropUrl, data.path, callback, true);
                  }, 400);
               }
        });

       // alert($input.val());
    });

});

// remove avatar
$(function(){

    $(document).on('click', '.img_delete', function(e){

        var $block = $(this).parents('.ch-cropper-block');

        e.preventDefault();
        e.stopPropagation();

        $block.removeClass('profile-avatar-active');

        $.post( '', { 'ch-cropper-delete-ajax': 1 }, function (data) {
                $block.find('img').attr('src', data.output);
                //$('img.user_avatar').attr('src', data.output);
            }
        );

    });
});
