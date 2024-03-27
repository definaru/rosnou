// Защита формы
$(document).on('beforeSubmit', 'form[data-plugin="antispam"]', function(e){
    $('<input type="hidden" name="inorobo" value="1"/>').appendTo($(this));
});
