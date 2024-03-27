$(document).ready(function () {
	$('#registrate').formValidation({
		framework: 'bootstrap',
		icon: {
			valid: 'fa fa-check',
			invalid: 'fa fa-times',
			validating: 'fa fa-refresh'
		},
		fields: {
			login: {
				validators: {
					notEmpty: {
						message: 'Поле «Логин» обязательно для заполнения'
					},
					stringLength: {
						min: 5,
						max: 30,
						message: 'Поле «Логин» должно быть не короче 5 и не длиннее 30 символов'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9_]+$/,
						message: 'Поле «Логин» может содержать только буквы латинского алфавита, цифры и символ нижнего подчеркивания'
					}
				}
			},
			email: {
				validators: {
					notEmpty: {
						message: 'Поле «E-mail» обязательно для заполнения'
					},
					emailAddress: {
						message: 'Введите валидный email адрес'
					}
				}
			},
			password: {
				validators: {
					notEmpty: {
						message: 'Поле «Пароль» обязательно для заполнения'
					},
					stringLength: {
						min: 3,
						max: 20,
						message: 'Поле «Пароль» должно быть не короче 3 и не длиннее 20 символов'
					}/*,
					 identical: {
					 field: 'password_confirm',
					 message: 'Пароль не совпадает с паролем в поле «Повторить пароль»'
					 }*/
				}
			},
			password_confirm: {
				validators: {
					notEmpty: {
						message: 'Поле «Повторить пароль» обязательно для заполнения'
					},
					stringLength: {
						min: 3,
						max: 20,
						message: 'Поле «Повторить пароль» должно быть не короче 3 и не длиннее 20 символов'
					},
					identical: {
						field: 'password',
						message: 'Пароль не совпадает с паролем в поле «Пароль»'
					}
				}
			},
			'data[new][lname]': {
				validators: {
					notEmpty: {
						message: 'Поле «Фамилия» обязательно для заполнения'
					}
				}
			},
			'data[new][fname]': {
				validators: {
					notEmpty: {
						message: 'Поле «Имя» обязательно для заполнения'
					}
				}
			},
			'data[new][father_name]': {
				validators: {
					notEmpty: {
						message: 'Поле «Отчество» обязательно для заполнения'
					}
				}
			},
			'data[new][org_name]': {
				validators: {
					notEmpty: {
						message: 'Поле «Название организации» обязательно для заполнения'
					}
				}
			},
			'data[new][dolzhnost]': {
				validators: {
					notEmpty: {
						message: 'Поле «Должность» обязательно для заполнения'
					}
				}
			},
			captcha: {
				threshold: 6,
				validators: {
					notEmpty: {
						message: 'Введите текст с картинки'
					},
					remote: {
						message: 'Вы ввели не верный текст с картинки',
						url: '/udata/users/checkCaptcha/',
						type: 'POST'
					}
				}
			}
		}
	});
});