$(document).ready(function() {
    $('#course_startDate').datetimepicker({
        format: 'YYYY-MM-DDTH:m:sz'
    });
    $('#course_endDate').datetimepicker({
        format: 'YYYY-MM-DDTH:m:sz',
        useCurrent: false //Important! See issue #1075
    });
    $("#course_startDate").on("dp.change", function (e) {
        $('#course_endDate').data("DateTimePicker").minDate(e.date);
    });
    $("#course_endDate").on("dp.change", function (e) {
        $('#course_startDate').data("DateTimePicker").maxDate(e.date);
    });
});
