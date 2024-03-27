$(document).ready(function () {

	$('#section-select').on('change', function(){
		Cookies.set("section_id", $(this).val());
		location.reload();
		return false;
	});

	$('.diploma-refresh').on('click', function(){
		var id = $(this).data('id');
		$.get('/udata/content/getPdf/' + id + '/1/');
		return false;
	});

});