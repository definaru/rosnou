
/**
 * Cropper initialization
 */
var initCropper = (function($popup, cropUrl, imageSrc, callback, fixedCrop) {

    'use strict';

    var $visualBox = $popup.find('.img-container');
    var console = window.console || { log: function () {} };

    var $image = $popup.find('#image');
    var $download = $popup.find('#download');
    var $dataX = $popup.find('#dataX');
    var $dataY = $popup.find('#dataY');
    var $dataHeight = $popup.find('#dataHeight');
    var $dataWidth = $popup.find('#dataWidth');
    var $dataRotate = $popup.find('#dataRotate');
    var $dataScaleX = $popup.find('#dataScaleX');
    var $dataScaleY = $popup.find('#dataScaleY');

    // Tooltip
    //$visualBox.css('visibility', 'hidden');

    //
    $popup.find('[data-toggle="tooltip"]').tooltip();

    // Устанавливаем картинку
    $image.attr('src', imageSrc  + '?' + (new Date()).getTime() );

    /**
     * Options
     */
    var $cropSize = $('.docs-toggles');

    //if( fixedCrop ){
        $cropSize.hide();
    //} else {
    //    $cropSize.show();
    //}

    var options = {
        aspectRatio: true, //fixedCrop ? fixedCrop : $('.docs-toggles label.active input').val(),
        preview: '.img-preview',
        dragMode: 'move',
        movable: true,
        viewMode: 1,
        autoCropArea: 1,
        crop: function (e) {
            $dataX.val(Math.round(e.x));
            $dataY.val(Math.round(e.y));
            $dataHeight.val(Math.round(e.height));
            $dataWidth.val(Math.round(e.width));
            $dataRotate.val(e.rotate);
            $dataScaleX.val(e.scaleX);
            $dataScaleY.val(e.scaleY);
        }
    }

    $image.on('built.cropper', function(){
        $visualBox.css('visibility', 'visible');
    })

    // init or replace image
    $image.data('init', true);
    $image.cropper('destroy').cropper(options);

    // set aspect ration
    $popup.find('.docs-toggles').on('change', 'input', function () {
        var $this = $(this);
        var name = $this.attr('name');
        $image.cropper('setAspectRatio', $this.val());
    });

    // Options
    $popup.find('[data-method="saveCanvas"]').unbind().on('click', function (e) {

        e.preventDefault();

        var data = $image.cropper('getData', true);

        data.src = imageSrc;
        data['ch-cropper-crop-ajax'] = 1;

        $popup.modal('hide');

        $.ajax( cropUrl, {
            type: 'post',
            data: data,
            dataType: 'json',
            success: function (data) {

                callback( data.path + '?' + (new Date()).getTime() );
            },
        });

        return false;
    });

    // Keyboard
    $(document.body).on('keydown', function (e) {

        if (!$image.data('cropper') || this.scrollTop > 300) {
          return;
        }

        switch (e.which) {
          case 37:
            e.preventDefault();
            $image.cropper('move', -1, 0);
            break;

          case 38:
            e.preventDefault();
            $image.cropper('move', 0, -1);
            break;

          case 39:
            e.preventDefault();
            $image.cropper('move', 1, 0);
            break;

          case 40:
            e.preventDefault();
            $image.cropper('move', 0, 1);
            break;
        }

    });

});
