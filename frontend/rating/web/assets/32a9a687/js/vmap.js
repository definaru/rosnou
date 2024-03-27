$(document).ready(function () {

	$.ajax({
		method: 'get',
		url: 'https://rating-web.ru/map/udata/content/getMap/1',
		dataType: 'json',
	})
		.done(function (data) {
			//console.log(data);

			var data_obj = {};

			for (var index in data) {
			// for (index = 0; index < data.length; ++index) {
				//console.log(data);
				/*data_obj[data[index].code] = [
					'<span class="colorbluedark">'+data[index].average+'<br><small>средний балл</small></span>',
					'<div class="dotted-horz-divider"></div>',
					data[index].stars
				];*/
				data_obj[data[index].code] = [
					'<div class="dotted-horz-divider"></div>',
					'<span class="colorbluedark">'+data[index].total+'<br><small>'+data[index].text+'</small></span>'
				];
			}

			// MAP RUSSIA
			colorRegion = '#12aaeb'; // Цвет всех регионов
			//selectRegion = '#12aaeb'; // Цвет изначально подсвеченных регионов
			selectRegion = '#0088B9'; // Цвет изначально подсвеченных регионов

			highlighted_states = {};

			/* Массив подсвечиваемых регионов, указанных в массиве data_obj */
			for (iso in data_obj) {
				highlighted_states[iso] = selectRegion;
			}

			/* Выводим список объектов из массива */
			$(document).ready(function () {
				for (region in data_obj) {
					for (obj in data_obj[region]) {
						$('.list-object').append('<li><a href="' + selectRegion + '" id="' + region + '" class="focus-region">' + data_obj[region][obj] + ' (' + region + ')</a></li>');
					}
				}
			});

			/* Подсветка регионов при наведении на объекты */
			$(function () {
				$('.focus-region').mouseover(function () {
					iso = $(this).prop('id');
					fregion = {};
					fregion[iso] = focusRegion;
					$('#vmap').vectorMap('set', 'colors', fregion);
				});
				$('.focus-region').mouseout(function () {
					c = $(this).attr('href');
					cl = (c === '#') ? colorRegion : c;
					iso = $(this).prop('id');
					fregion = {};
					fregion[iso] = cl;
					$('#vmap').vectorMap('set', 'colors', fregion);
				});
			});

			/*END MAP RUSSIA*/

			$('#vmap').vectorMap({
				map: 'russia',
				backgroundColor: 'transparent',
				borderColor: '#ffffff',
				borderWidth: 1,
				color: colorRegion,
				colors: highlighted_states,
				hoverColor: true,
				hoverColor: '#16a085',
				enableZoom: false,
				showTooltip: true,

				// Отображаем объекты если они есть
				onLabelShow: function (event, label, code) {
					name = '<div class="jqmap panel-header">' + label.text() + '</div>';
					if (data_obj[code]) {
						list_obj = '<div class="jqmap stat">';
						for (ob in data_obj[code]) {
							list_obj += '<span>' + data_obj[code][ob] + '</span>';
						}
						list_obj += '</div>';
					} else {
						list_obj = '';
					}
					label.html(name + list_obj);
					list_obj = '';
				},
				// Клик по региону
				onRegionClick: function (element, code, region) {
					window.location = '/uchastniki/?MembersSearchForm[subject_id]=' + data[code]['subject_id'];
				}
			});
		});

});