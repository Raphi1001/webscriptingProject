"use strict";
function loadAppointmentList() {
    $("#pageContent").load("templates/appointmentList.html", function () {
        callAppointmentListData();
    });
}
function loadNewAppointmentForm() {
    $("#pageContent").load("templates/newAppointmentForm.html");
}
function loadAppointmentDetails(appId) {
    $("#pageContent").load("templates/appointmentDetails.html", function () {
        $("#commentForm").attr("onsubmit", "submitNewCommentForm(" + appId + ")");
        callAppointmentDetailsData(appId);
        callAppointmentCommentsData(appId);
        callAppointDatesData(appId);
        callAppointmentDateVoteData(appId);
    });
}
