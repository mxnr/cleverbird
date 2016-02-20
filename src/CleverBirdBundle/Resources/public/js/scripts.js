$(document).ready(function() {
    $('.dropdown-toggle').dropdown();

    $('#myTabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });

    $('[data-toggle="tooltip"]').tooltip();
});
