$(document).ready(function () {
	$('#administrative_district').hide();

	$('#application')
		.find('[name="data[new][site_category]"]')
		.select2({
			templateResult: formatState
		})
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][site_category]');
		})
		.end()
		.find('[name="data[new][site_address]"]')
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][site_address]');
		})
		.end()
		.find('[name="data[new][site_name]"]')
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][site_name]');
		})
		.end()
		.find('[name="data[new][site_full_name]"]')
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][site_full_name]');
		})
		.end()
		.find('[name="data[new][federal_district]"]')
		.select2({
			templateResult: formatState
		})
		//Revalidate
		.change(function (e) {
			//$('#application').formValidation('revalidateField', 'data[new][federal_district]');
			$('#application').data('formValidation').enableFieldValidators('data[new][district]', true);

			$.ajax({
					url: "/udata/data/getGuidedItems/",
					dataType: 'json',
					delay: 250,
					data: {
						type_id: '125',
						parent_id: $(this).val()
					},
					beforeSend: function () {
						$('select[name="data[new][subject_federation]"] option[value]').remove();
						$('select[name="data[new][district]"] option[value]').remove();
						$('select[name="data[new][district]"]').select2("val", "");
					},
					cache: true
				})
				.then(function (data) {
					$('select[name="data[new][subject_federation]"]').select2({
						data: data,
						templateResult: formatState,
						width: '100%',
						"language": {
							"noResults": function () {
								return 'Ничего не найдено';
							}
						}
					});
				});
		})
		.end()
		.find('[name="data[new][subject_federation]"]')
		.select2({
			templateResult: formatState,
			"language": {
				"noResults": function () {
					return 'Ничего не найдено';
				}
			}
		})
		//Revalidate
		.change(function (e) {
			//$('#application').formValidation('revalidateField', 'data[new][subject_federation]');

			if ($(this).val() == 422) {
				$('#district').hide();
				$('#locality').hide();
				$('#administrative_district').show();
				$('#application').data('formValidation').enableFieldValidators('data[new][locality]', false);
			}
			else {
				$('#district').show();
				$('#locality').show();
				$('#administrative_district').hide();
				$('#application').data('formValidation').enableFieldValidators('data[new][locality]', true);
			}

			$('#application').data('formValidation').enableFieldValidators('data[new][district]', true);//.revalidateField('data[new][district]');

			$.ajax({
					url: "/udata/data/getGuidedItems/",
					dataType: 'json',
					delay: 250,
					data: {
						type_id: '126',
						parent_id: $(this).val()
					},
					beforeSend: function () {
						$('select[name="data[new][district]"] option[value]').remove();
					},
					cache: true
				})
				.then(function (data) {
					if(data.length) {
						$('select[name="data[new][district]"]').select2({
							data: data,
							templateResult: formatState,
							width: '100%',
							"language": {
								"noResults": function () {
									return 'Ничего не найдено';
								}
							}
						});
					} else {
						$('#application').data('formValidation').enableFieldValidators('data[new][district]', false);//.revalidateField('data[new][district]');
					}
				});
		})
		.end()
		.find('[name="data[new][district]"]')
		.select2({
			templateResult: formatState,
			"language": {
				"noResults": function () {
					return 'Ничего не найдено';
				}
			}
		})
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][district]');
		})
		.end()
		.find('[name="data[new][administrative_district]"]')
		.select2({
			templateResult: formatState,
			width: '100%',
			"language": {
				"noResults": function () {
					return 'Ничего не найдено';
				}
			}
		})
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][administrative_district]');
		})
		.end()
		.find('[name="data[new][locality]"]')
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][locality]');
		})
		.end()
		.find('[name="data[new][fio_direktora]"]')
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][fio_direktora]');
		})
		.end()
		.find('[name="data[new][site_email]"]')
		//Revalidate
		.change(function (e) {
			$('#application').formValidation('revalidateField', 'data[new][site_email]');
		})
		.end()
		.formValidation({
			framework: 'bootstrap',
			excluded: ':disabled',
			icon: {
				valid: 'fa fa-check',
				invalid: 'fa fa-times',
				validating: 'fa fa-refresh'
			},
			fields: {
				'data[new][site_category]': {
					validators: {
						notEmpty: {
							message: 'Поле «Категория сайта» обязательно для заполнения'
						}
					}
				},
				'data[new][site_address]': {
					validators: {
						notEmpty: {
							message: 'Поле «Адрес сайта» обязательно для заполнения'
						},
						/*uri: {
							message: 'Введите верный адрес сайта, начиная с http://'
						}*/
						regexp: {
							regexp: /^([a-z][a-z0-9\*\-\.]*):\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*(?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:(?:[a-z0-9_\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?](?:[\w#!:\.\?\+=&@!$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/i,
							message: 'Введите верный адрес сайта, начиная с http://'
						}
					}
				},
				'data[new][site_name]': {
					validators: {
						notEmpty: {
							message: 'Поле «Название сайта» обязательно для заполнения'
						}
					}
				},
				'data[new][site_full_name]': {
					validators: {
						notEmpty: {
							message: 'Поле «Официальное название образовательной организации» обязательно для заполнения'
						}
					}
				},
				'data[new][federal_district]': {
					validators: {
						notEmpty: {
							message: 'Поле «Федеральный округ» обязательно для заполнения'
						}
					}
				},
				'data[new][subject_federation]': {
					validators: {
						notEmpty: {
							message: 'Поле «Субъект Федерации» обязательно для заполнения'
						}
					}
				},
				'data[new][district]': {
					validators: {
						notEmpty: {
							message: 'Поле «Район» обязательно для заполнения'
						}
					}
				},
				'data[new][administrative_district]': {
					validators: {
						notEmpty: {
							message: 'Поле «Административный округ» обязательно для заполнения'
						}
					}
				},
				'data[new][locality]': {
					validators: {
						notEmpty: {
							message: 'Поле «Населенный пункт» обязательно для заполнения'
						}
					}
				},
				'data[new][fio_direktora]': {
					validators: {
						notEmpty: {
							message: 'Поле «ФИО директора ОУ» обязательно для заполнения'
						}
					}
				},
				'data[new][site_email]': {
					validators: {
						notEmpty: {
							message: 'Поле «Эл. почта» обязательно для заполнения'
						},
						emailAddress: {
							message: 'Введите валидный email адрес'
						}
					}
				},
				'data[new][no_ad]': {
					err: '.error-message',
					validators: {
						choice: {
							min: 1,
							max: 1,
							message: 'Укажите, присутствует ли на Вашем сайте реклама или нет'
						}
					}
				}
			}
		}).on('success.field.fv', function (e, data) {
			/*if (data.field == 'data[new][no_ad]' || data.field == 'data[new][site_category]' || data.field == 'data[new][federal_district]' || data.field == 'data[new][subject_federation]' || data.field == 'data[new][district]' || data.field == 'data[new][administrative_district]') {
				data.element.data('fv.icon').hide();
			}*/

			var $parent = data.element.parents('.form-group');
			$parent.removeClass('has-success');
			data.element.data('fv.icon').hide();

		}).on('err.field.fv', function (e, data) {
			if (data.field == 'data[new][no_ad]') {
				/*var $parent = data.element.parents('.form-group');
				 $parent.removeClass('has-success');*/
				data.element.data('fv.icon').hide();
			}
		}).on('change', '[name="data[new][subject_federation]"]', function (e) {
			var app = $('#application'),
				subject_federation = app.find('[name="data[new][subject_federation]"]').val(),
				fv = app.data('formValidation');

			if (subject_federation == 422) {
				fv.enableFieldValidators('data[new][district]', false).revalidateField('data[new][district]');
				fv.enableFieldValidators('data[new][administrative_district]', true).revalidateField('data[new][administrative_district]');
			} else {
				fv.enableFieldValidators('data[new][district]', true).revalidateField('data[new][district]');
				fv.enableFieldValidators('data[new][administrative_district]', false).revalidateField('data[new][administrative_district]');
			}
		});

	/**
	 * Application form reset
	 */
	$('.app-form-reset').on('click', function(){
		$('#application').resetForm();
		$('[name="data[new][site_category]"]').select2("val", "");
		$('[name="data[new][federal_district]"]').select2("val", "");
		$('[name="data[new][subject_federation]"]').select2("val", "");
		$('[name="data[new][district]"]').select2("val", "");
		$('[name="data[new][administrative_district]"]').select2("val", "");
		return false;
	});

	function formatState(state) {
		if (!state.id) {
			return state.text;
		}
		var $state = $(
			'<div class="option"><div class="option-text"> ' + state.text + '</div></div>'
		);
		return $state;
	}

});