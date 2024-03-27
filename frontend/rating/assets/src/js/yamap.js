/* Yandex Map */

ymaps.ready(function () {

	$('.ya-map').each(function () {

			var $this = $(this),
				id = $this.attr('id'),
				coords,
				zoom = $this.data('zoom'),
				phone = $this.data('phone'),
				address = $this.data('address'),
				work = $this.data('work'),
				map = false;

			if (typeof address != 'undefined') {

				ymaps.geocode(address, {results: 1}).then(function (res) {
					var firstGeoObject = res.geoObjects.get(0);

					coords = firstGeoObject.geometry.getCoordinates();

					map = new ymaps.Map(id, {
						center: coords,
						zoom: (zoom) ? zoom : 16
					});

					myPlacemark = new ymaps.Placemark(coords, {
						/*iconContent: '',
						 balloonContentHeader: '',
						 balloonContentBody: address,
						 balloonContentFooter: ''*/
					}, {
						iconImageHref: '/templates/default/images/pin.png',
						iconImageSize: [49, 71],
						iconImageOffset: [-25, -71],
						balloonContentSize: [265, 150],
						balloonLayout: "default#imageWithContent",
						balloonImageHref: '/templates/default/images/balloon.png',
						balloonImageSize: [265, 192],
						balloonImageOffset: [-134, -220],
						hideIconOnBalloonOpen: false
					});

					myBalloonLayout = ymaps.templateLayoutFactory.createClass(
						'<div style="color: #12aaeb; font-weight: bold; font-size: 14px; text-align: center; margin-top: 30px; padding: 0px 20px"><p>' + address + '</p></div>' +
						'<p style="color: #597a96; text-align: center; padding: 0 20px; margin-bottom: 0;">телефон: '+ phone +'</p>' +
						'<p style="color: #597a96; text-align: center; padding: 0 20px 10px 20px; margin-bottom: 0;">'+ work +'</p>'
					);

					ymaps.layout.storage.add('rosnou', myBalloonLayout);
					myPlacemark.options.set({
						balloonContentBodyLayout: 'rosnou',
						balloonShadow: false
					});

					map.geoObjects.add(myPlacemark);

					map.controls
						.add('zoomControl', {left: 7, top: 62})
						.add('typeSelector')
						.add('mapTools');

					/*var myMap = new ymaps.Map('map', {
					 center: [55.73413905, 37.59800602],
					 zoom: 15
					 }),

					 myPlacemark = new ymaps.Placemark([55.73393743, 37.59565715], {
					 address: 'Фрунзенская набережная, 4',
					 address2: 'Москва 119034',
					 tel: '8(495)223-55-55',
					 work: '9:00-18:00'
					 }, {
					 iconImageHref: '/templates/default/images/pin.png',
					 iconImageSize: [49, 71],
					 iconImageOffset: [-25, -71],
					 balloonContentSize: [265, 150],
					 balloonLayout: "default#imageWithContent",
					 balloonImageHref: '/templates/default/images/balloon.png',
					 balloonImageSize: [265, 192],
					 balloonImageOffset: [-134, -220],
					 hideIconOnBalloonOpen: false

					 }),

					 myBalloonLayout = ymaps.templateLayoutFactory.createClass(
					 '<div style="color: #12aaeb; font-weight: bold; font-size: 14px; text-align: center; margin-top: 30px; padding: 0px 20px"><p>$[properties.address]</p>' +
					 '<p>$[properties.address2]</div>' +
					 '<p style="color: #597a96; text-align: center; padding: 0 20px; margin-bottom: 0;">телефон: $[properties.tel]</p>' +
					 '<p style="color: #597a96; text-align: center; padding: 0 20px 10px 20px; margin-bottom: 0;">$[properties.work]</p>'
					 );

					 ymaps.layout.storage.add('rosnou', myBalloonLayout);
					 myPlacemark.options.set({
					 balloonContentBodyLayout: 'rosnou',
					 balloonShadow: false
					 });


					 myMap.geoObjects.add(myPlacemark);*/
				}, function (err) {
					console.log(err.message);
				});
			}
		}
	);
});




