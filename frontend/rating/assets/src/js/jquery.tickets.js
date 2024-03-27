$(document).ready(function () {

	$('.ticket-open').on('click', function () {
		$('#ticket-form input[name=result_id]').val($(this).data('result'));
		$('#ticket-form input[name=criteria_id]').val($(this).data('criteria'));
	});

	$('.chat-controls-btn').on('click', function () {
		var $btn = $(this).button('loading');
		$('#ticket-form').ajaxSubmit({beforeSubmit: formValidation, success: showResponse}).resetForm();
		$btn.button('reset');
		return false;
	});

	function formValidation(arr, $form, options) {
		return $form.find('textarea[name=message]').val() != '';
	}

	function showResponse(responseText, statusText, xhr, $form) {
		var result = tmpl('tmpl-demo', responseText),
			tickets = $('#tickets'),
			container = tickets.find('.modal-body');

		container.append(result);
		var scrollTo = container.find('.message:last');
		container.animate({
			scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
		}, 'slow');

		window.setTimeout(function () {
			tickets.modal('hide');
		}, 700);
	}

	$('#tickets')
        .on('show.bs.modal', function (e) {
            var modal = $(this),
                button = $(e.relatedTarget),
                criteriaResultId = button.data('result'),
                criteriaName = $('label[for=criteria-' + button.data('criteria') + ']').text();

            $('#panel-descr').text(criteriaName);

            console.log('/udata/users/tickets/' + criteriaResultId);

            $.ajax({
                    method: 'get',
                    url: '/udata/users/tickets/' + criteriaResultId,
                    dataType: 'json'
                })
                .done(function (data) {
                    var result = tmpl('tmpl-demo', data);
                    modal.find('.modal-body').html(result);

                    /// Удаление сообщений
                    $('.message .close').on('click', function () {
                        var $this = $(this),
                            id = $(this).data('id'),
                            $parent = $this.parents('.message');

                        $.ajax({
                            method: 'DELETE',
                            url: '/udata/users/tickets/del/',
                            data: {id: id, method: 'delete'}
                        });

                        $parent.fadeOut('slow');
                    });
                });
	    })
        .on('hidden.bs.modal', function (e) {
            $(this).find('.modal-body').html('');
            $('#panel-descr').text('');
	    });
});
