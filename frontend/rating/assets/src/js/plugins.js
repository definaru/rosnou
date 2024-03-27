/**
 * Set-cookie plugin
 */
$(document).on('click', '[data-setcookie]', function(e){

    var $button = $(this);
    var cookieName = $button.attr('data-setcookie');

	Cookies.set(cookieName, 1, { expires: 999999, path: '/' });

    console.log('Cookie with name "' + cookieName + '" was set');
});
