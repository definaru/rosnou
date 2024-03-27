$(document).ready(function () {
	$('#administrative_district').hide();

	$('.select-width250').select2({
		templateResult: formatState,
		width: '250px'
	});

	$('.select-width190').select2({
		templateResult: formatState,
		width: '190px'
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
	
	$('#filter')
		.find('[name="fields_filter[subject_federation]"]')
		.select2({
			templateResult: formatState,
			width: '250px',
			"language": {
				"noResults": function () {
					return 'Ничего не найдено';
				}
			}
		})
		.change(function (e) {
			if ($(this).val() == 422) {
				$('#district').hide();
				$('#administrative_district').show();
			}
			else {
				$('#district').show();
				$('#administrative_district').hide();
			}

			/*$.ajax({
					url: "/udata/data/getGuidedItems/",
					dataType: 'json',
					delay: 250,
					data: {
						type_id: '126',
						parent_id: $(this).val()
					},
					beforeSend: function () {
						$('select[name="fields_filter[district]"] option[value]').remove();
					},
					cache: true
				})
				.then(function (data) {
					if (data.length) {
						$('select[name="fields_filter[district]"]').select2({
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

					}
				});*/
		})
		.end();

});