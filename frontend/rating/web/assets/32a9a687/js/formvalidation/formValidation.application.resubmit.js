$(document).ready(function () {
	if ($('button.resubmit').length) {
		$('#application').formValidation({
			framework: 'bootstrap',
			icon: {
				valid: 'fa fa-check',
				invalid: 'fa fa-times',
				validating: 'fa fa-refresh'
			},
			fields: {
				user_comment: {
					validators: {
						notEmpty: {
							message: 'Необходимо добавить комментарий к заявке'
						}/*,
						stringLength: {
							min: 5,
							max: 30,
							message: 'Поле «Логин» должно быть не короче 5 и не длиннее 30 символов'
						},
						regexp: {
							regexp: /^[a-zA-Z0-9_]+$/,
							message: 'Поле «Логин» может содержать только буквы латинского алфавита, цифры и символ нижнего подчеркивания'
						}*/
					}
				}
			}
		});
	}
});