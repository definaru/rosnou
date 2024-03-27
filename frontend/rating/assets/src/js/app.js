var APP = {
    modules: {
        addSiteForm: {
            bindCountrySubjectDropdown: function() {
                var subjectDropdown = $('#siteform-subject_id');

                var currentValue = subjectDropdown.data('current-value');

                $(document).on('change', '#siteform-district_id', function() {
                    var districtId =  $(this, 'option:selected').val();

                    subjectDropdown.html('');
                    subjectDropdown.data('placeholder', 'Загрузка...');
                    bindSelect2(subjectDropdown);

                    $.getJSON(APP.utils.url('country/subjects/' + districtId), function(subjects) {
                        var firstSelected = false;

                        $.each(subjects, function(value, label) {
                            var option = $('<option />');
                            option.val(value);
                            option.html(label);

                            if(currentValue == value) {
                                option.attr('selected', 'selected');
                            }

                            if(!currentValue && !firstSelected) {
                                option.attr('selected', 'selected');
                                firstSelected = true;
                            }

                            subjectDropdown.append(option);
                        });

                        subjectDropdown.data('placeholder', 'Субъект федерации');
                        bindSelect2(subjectDropdown);

                        subjectDropdown.trigger('change');
                    });
                });

                $('#siteform-district_id').trigger('change');
            },
            bindSubjectMoscow: function() {
                $(document).on('change', '#siteform-subject_id', function() {
                    var subjectId =  $(this, 'option:selected').val();
                    var subjectLabel = $('#siteform-subject_id option[value=' + subjectId + ']').html();

                    if(subjectLabel == 'Москва') {
                        $('#siteform-location').attr('placeholder', 'Административный округ');
                    } else {
                        $('#siteform-location').attr('placeholder', 'Населенный пункт');
                    }
                });
            }
        }
    },
    utils: {
        url: function(url) {
            return '/' + url + '/';
        }
    }
};

(function($) {
    "use strict";
    var $container = $('.masonry-news');
    $container.imagesLoaded( function() {
        $container.masonry({
            columnWidth: '.masonry-item',
            itemSelector: '.masonry-item'
        });
    });

    bindPostMethodForLinks();
})(jQuery);

function bindPostMethodForLinks() {
    $(document).on('click', "a[data-method]", function () {
        var method = $(this).data('method');
        var action = $(this).attr('href');

        var form = $("<form></form>");

        form.append('<input type="hidden" name="_method" value="' + method + '">');
        var csrfInput = $("<input />");
        csrfInput.prop({
            'type': 'hidden'
            //'name': CSRF_TOKEN_NAME,
            //'value': CSRF_TOKEN
        });
        form.append(csrfInput);

        form.prop({
            'method': 'POST',
            'action': action
        });
        $('body').append(form);
        form.submit();
        return false;
    });
}