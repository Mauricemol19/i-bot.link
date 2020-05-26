$(function() {
    $(".subTable tbody").sortable({
        scroll: false,
        helper: fixHelperModified,
        stop: updateIndex,
        sort: function(event, ui) {
            var $target = $(event.target);
            if (!/html|body/i.test($target.offsetParent()[0].tagName)) {
                var top = event.pageY - $target.offsetParent().offset().top - (ui.helper.outerHeight(true) / 2);
                ui.helper.css({'top' : top + 'px'});
            }},
        update: function(event, ui) {
            saveOrderClick();
        }
    }).disableSelection();
});

var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
    });
    return $helper;
},
updateIndex = function(e, ui) {
    $('td.index', ui.item.parent()).each(function (i) {
        $(this).html(i + 1);
    });
};

function saveOrderClick() {
    // ----- Retrieve the li items inside our sortable list
    var i = 1;

    $(".subTable").each(function () {
        var items = $("#sort" + i + " tbody td");

        var sort = [];
        var index = 0;

        // ----- Iterate through each li, extracting the ID embedded as an attribute
        items.each( function(intIndex) {
            if ($(this).attr("id")) {
                sort[index] = $(this).attr("id");
                index++;
            }
        });

        $.post( "/room", { sort: sort.join(",") } ).done(function( data ) {});

        i++;
    });
}