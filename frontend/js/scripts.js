"use strict";
function loadData() {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { param: 10 },
        dataType: "json",
        success: function (result) {
            $("#test").text(result);
        },
        error: function () {
            $("#test").text("error");
        }
    });
}
$(document).ready(function () {
    loadAppointmentsList();
});
function loadAppointmentsList() {
    $("#pageContent").load("templates/appointmentList.html");
}
function createAppointment() {
    $("#pageContent").load("templates/newAppointmentForm.html");
}
