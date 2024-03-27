
$( function() {

    var $table = $( "table.kv-grid-table" ).find('tbody');
    var dataUrl = $("table.kv-grid-table" ).attr('data-url');
    var dataPage = $("table.kv-grid-table" ).attr('data-page');
    var dataPageSize = $("table.kv-grid-table" ).attr('data-pageSize');
    $table.sortable({
        handle: ".handle",
        helper: 'clone',
        placeholder: "ui-state-highlight",
        update: function(event, ui) {
            var values = [];
            // ui.item - DOM element
            var tbl = $("table.kv-grid-table tbody");

            var trows = tbl[0].rows;

            $.each(trows, function (index, row) {
                var id = $(row).data('key');
                values.push(id);

            });

            if(dataUrl){
                $.ajax({
                    url: dataUrl,
                    type: 'POST',
                    data: {
                        values: values,
                        pageCurrent: dataPage,
                        pageSize: dataPageSize,
                    },
                    cache: false
                });
            }
        }
    });

    $table.disableSelection();

    //$table.find('tr').css('background-color', 'white');

});
