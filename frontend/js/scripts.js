"use strict";
var dateCount = 1;
function loadData() {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { param: 10 },
        dataType: "json",
        success: function (result) {
            console.log("result: " + result);
        },
        error: function () {
            $("#test").text("error");
        }
    });
}
function loadAppointmentList() {
    $("#pageContent").load("templates/appointmentList.html");
}
function loadNewAppointmentForm() {
    $("#pageContent").load("templates/newAppointmentForm.html");
}
function loadAppointmentDetails() {
    $("#pageContent").load("templates/appointmentDetails.html");
}
/* ein dreck*/
var button = document.querySelector("#test");
if (button)
    button.addEventListener("click", handleToggle);
function handleToggle() {
    this.classList.toggle("clicked");
}
function addDate() {
    ++dateCount;
    var newItem = '<div class="mb-1"><input type="date" class="form-control" id="appointmentDateOption-1"></div>';
    $(newItem).hide().appendTo("#dateGroup").slideDown(500); //adds new item to list
}
