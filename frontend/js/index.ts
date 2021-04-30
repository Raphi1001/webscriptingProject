//loads the appointmentList template
function loadAppointmentList() {
   $("#pageContent").load("templates/appointmentList.html", function () {
      callAppointmentListData();
   });
}

//loads the newAppointmentForm template
function loadNewAppointmentForm() {
   $("#pageContent").load("templates/newAppointmentForm.html");
}

//loads the appointmentDetails template and adds button fuctionality 
function loadAppointmentDetails(appId: number) {
   $("#pageContent").load("templates/appointmentDetails.html", function () {
      $("#commentForm").attr("onsubmit", "submitNewCommentForm(" + appId + ")");
     
      $("#delBtn").attr("onclick", "deleteAppointment(" + appId + ")");

      callAppointmentDetailsData(appId);
      callAppointmentCommentsData(appId);
      callAppointDateOptionsData(appId);
   });
}

