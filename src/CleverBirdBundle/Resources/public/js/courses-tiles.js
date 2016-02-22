$(window).on("load", function() {
    var grid = $('.grid');
    if (grid.length > 0) {
        grid.masonry({
            // options
            itemSelector: '.grid-item',
            columnWidth: '.grid-item',
            isFitWidth: true
        });
    }
});
