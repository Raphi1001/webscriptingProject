"use strict";
function callAppointmentListData() {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointment" },
        dataType: "json",
        success: function (result) {
            $("#pageContent").load("templates/appointmentList.html", function () {
                for (var i = 0; i < result.length; ++i) {
                    var appointmentTitle = result[i].title;
                    var appointmentDescription = result[i].description;
                    $("#allAppointments").append('<div class="col-sm-12 col-md-4 col-lg-3 d-flex my-3 justify-content-center"><div class="card"><div class="card-body"><h5 class="card-title">' + appointmentTitle + '</h5><p class="card-text">' + appointmentDescription + '</p><button class="btn btn-dark" onclick="loadAppointmentDetails(\'' + result[i].app_id + '\')">Show Appointment Details</button></div></div></div>');
                }
            });
        },
        error: function () {
            console.log("error");
        }
    });
}
