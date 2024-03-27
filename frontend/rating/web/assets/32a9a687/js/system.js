$(document).ready(function () {

	$(".switch, .section-switch").bootstrapSwitch({
		onText: 'Вкл',
		offText: 'Выкл'
	});

	$('.switch').on('switchChange.bootstrapSwitch', function (event, state) {
		/*console.log(this); // DOM element
		 console.log(event); // jQuery event
		 console.log(state); // true | false*/
		$('#system-settings').ajaxSubmit();
	});

	$('.section-switch').on('switchChange.bootstrapSwitch', function (event, state) {
		/*console.log(this); // DOM element
		 console.log(event); // jQuery event
		 console.log(state); // true | false*/
		var form = $(this).parents('form');
		form.ajaxSubmit({
			data: {state: state}
		});
	});

	/**
	 * Модальное окно удаление среза
	 */
	$('#section-delete').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget),
			object_id = button.data('id');
		$(this).find('input[name=object_id]').val(object_id);
	}).on('hide.bs.modal', function (event) {
		var button = $(event.relatedTarget),
			object_id = button.data('id');
		$(this).find('input[name=object_id]').val(null);
	});

	/**
	 * Удаление самообследований
	 */
	$('#submit-delete-self').on('click', function () {
		var $btn = $(this).button('loading'),
			form = $('#delete-self-examinations'),
			body = form.find('.modal-body');

		body.find('.text').hide();
		body.find('.progress').toggleClass('hide');

		$.ajax('/users/examination/delete/.json', {
			type: 'get',
			dataType: 'json',
			complete: function () {
				body.find('.complete').toggleClass('hide');
				body.find('.progress').toggleClass('hide');
			}
		});

		$btn.button('reset');

		return false;
	});

	$('#delete-examinations').on('hidden.bs.modal', function (e) {
		var form = $('#delete-self-examinations'),
			body = form.find('.modal-body');
		body.find('.text').show();
		body.find('.complete').addClass('hide');
	})


});