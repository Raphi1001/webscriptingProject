function loadAppointmentList() {
   $("#pageContent").load("templates/appointmentList.html", function () {
      callAppointmentListData();
   });
}

function loadNewAppointmentForm() {
   $("#pageContent").load("templates/newAppointmentForm.html");
}

function loadAppointmentDetails(appId: number) {
   $("#pageContent").load("templates/appointmentDetails.html", function () {
      $("#commentForm").attr("onsubmit", "submitNewCommentForm(" + appId + ")");
     
      $("#delBtn").attr("onclick", "deleteAppointment(" + appId + ")");

      callAppointmentDetailsData(appId);
      callAppointmentCommentsData(appId);
      callAppointDateOptionsData(appId);
   });
}

