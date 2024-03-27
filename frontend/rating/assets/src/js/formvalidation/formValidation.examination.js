$(document).ready(function () {

	var self = $('#self-examination'),
		ex = $('#examination');

	ex.formValidation({
		framework: 'bootstrap',
		icon: {
			valid: 'fa fa-check',
			invalid: 'fa fa-times',
			validating: 'fa fa-refresh'
		},
		fields: {
			input: {
				selector: '.float-input',
				validators: {
					notEmpty: {
						message: 'Укажите количество баллов'
					},
					regexp: {
						regexp: /^([0-9]+\.[0-9])+$/,
						message: 'Можно использовать только дробные числа с точкой'
					}
				}
			}
		}
	}).on('success.field.fv', function (e, data) {
		var $parent = data.element.parents('.form-group');
		$parent.removeClass('has-success');
		data.element.data('fv.icon').hide();

	}).on('err.field.fv', function (e, data) {
		data.element.data('fv.icon').hide();
	});

	self.formValidation({
		framework: 'bootstrap',
		icon: {
			valid: 'fa fa-check',
			invalid: 'fa fa-times',
			validating: 'fa fa-refresh'
		},
		fields: {
			input: {
				selector: '.form-control',
				row: '.col-sm-6',
				validators: {
					regexp: {
						/*regexp: /^http?:\/\/((?:[a-z0-9а-я\-]+\.)+[a-zа-я]{2,6}(?:\/[^/#?]+)+\.(?:php|htm|html|shtml|asp)|(?:[a-z0-9а-я\-]+\.)+[a-zA-Zа-я]{2,6}(?:[^\s.]*)|(?:[a-z0-9а-я\-]+\.)+[a-zа-я]{2,6}(?:\/[^/#?]+)+\.(?:php|htm|html|shtml|asp)([^=]+)\=([^\&]+)|(?:[a-z0-9а-я\-]+\.)+[a-zа-я]{2,6}(?:\/[^/#?]+)+\.(?:php|htm|html|shtml|asp)?=?([\/\w-_\?\)%\.\(\*\[\]\^<>\\]*)?[\&]?([\w-_]*)?=?([\/\w-_\?\)% \.\(\*\[\]\^<>\\]*)?[\&]?([\w-_]*)?=?([\/\w-_\?\)%\.\(\*\[\]\^<>\\]*)?[\&] ?([\w-_]*)?=?([\/\w-_\?\)%\.\(\*\[\]\^<>\\]*)?|(?:[0-9a-z][0-9a-z-]{0,62}[0-9a-z]\.xn--p1ai)(?:\/[^/#?]+)+\.(?:php|htm|html|shtml|asp)|(?:[0-9a-z][0-9a-z-]{0,62}[0-9a-z]\.xn--p1ai)(?:\/[^/#?]+)+\.(?:php|htm|html|shtml|asp)([^=]+)\=([^\&]+)|(?:[0-9a-z][0-9a-z-]{0,62}[0-9a-z]\.xn--p1ai)(?:\/[^/#?]+)+\.(?:php|htm|html|shtml|asp)?=?([\/\w-_\?\)%\.\(\*\[\]\^<>\\]*)?[\&]?([\w-_]*)?=?([\/\w-_\?\)% \.\(\*\[\]\^<>\\]*)?[\&]?([\w-_]*)?=?([\/\w-_\?\)%\.\(\*\[\]\^<>\\]*)?[\&] ?([\w-_]*)?=?([\/\w-_\?\)%\.\(\*\[\]\^<>\\]*)?|(?:[0-9a-z][0-9a-z-]{0,62}[0-9a-z]\.xn--p1ai)(?:[^\s.]*))$/,*/
						//message: 'Нельзя указывать ссылки на какие-либо документы'

						regexp: /^([a-z][a-z0-9\*\-\.]*):\/\/(?:(?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*(?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@)?(?:(?:[a-z0-9_\-\.]|%[0-9a-f]{2})+|(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\]))(?::[0-9]+)?(?:[\/|\?](?:[\w#!:\.\?\+=&@!$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})*)?$/i,
						message: 'Введен некорректный адрес страницы'
					}
				}
			}
		}
	}).on('success.field.fv', function (e, data) {
		var $parent = data.element.parents('.form-group');
		$parent.removeClass('has-success');
		data.element.data('fv.icon').hide();

	});

	/**
	 * Запрещаем отправку форму по нажатию на Enter
	 */
	ex.on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault();
			return false;
		}
	});

	/**
	 * Запрещаем отправку форму по нажатию на Enter
	 */
	self.on('keyup keypress', function(e) {
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault();
			return false;
		}
	});

	function fieldValue($field){

		var value = $field.val();

		if( $field.is('[data-score]') ){
			value = $field.data('score');
		}

		return value;
	}

	/**
	 * Подсчет кол-ва баллов при экспертизе
	 */
	function summary() {
		
		var summ = 0;

		$('#examination input[type=radio]:checked, #examination input[type=text]').each(function () {
			
			//console.log($(this).attr('name') + ': ' + parseFloat($(this).val()));
			var $field = $(this);	
			var value = fieldValue($field);

			if( value ) {
				summ = summ + parseFloat(value);
			}
		});

		//console.log(summ);

		$('#summary').text(summ);
	}

	summary();

	/**
	 * Пересчет кол-ва баллов при изменении полей
	 */
	$('#examination input[type=radio], #examination input[type=text]').on('change', function(){
		
		summary();

		var $this = $(this);
		var value = fieldValue($this);

		if(value == 0) {
			$this.parents('.form-group').find('.ticket-open').trigger('click');
		}
	});

});
