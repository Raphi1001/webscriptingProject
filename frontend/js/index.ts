function loadAppointmentList() {
   $("#pageContent").load("templates/appointmentList.html", function () {
      callAppointmentListData();
   });
}

function loadNewAppointmentForm() {
   $("#pageContent").load("templates/newAppointmentForm.html");
}

function loadAppointmentDetails(appointmentTitle: number) {
   $("#pageContent").load("templates/appointmentDetails.html", function () {
      callAppointmentDetailsData(appointmentTitle);
   });
}
