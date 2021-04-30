function callAppointmentListData() {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointment" },
        dataType: "json",
        success: function (result: any[]) {
            $("#pageContent").load("templates/appointmentList.html", function () {
                for (var i = 0; i < result.length; ++i) {
                    var appointmentTitle = result[i].title;
                    var appointmentDescription = result[i].description;

                    var day1:Date = new Date(Date.now());
                    var day2:Date = new Date(result[i].vote_expire);
                    var difference = day1.getTime() - day2.getTime();
                   
                    if(difference > 0)
                   {
                    $("#allAppointments").append(
                        '<div class="col-sm-12 col-md-4 col-lg-3 d-flex my-3 justify-content-center"><div class="card"><div class="card-body"><h5 class="card-title">' + appointmentTitle + '</h5><p class="card-text text-danger">Expired</p><button class="btn btn-dark" onclick="loadAppointmentDetails(\'' + result[i].app_id + '\')">Show Appointment Details</button></div></div></div>')
                   }
                   else 
                   {
                    $("#allAppointments").append(
                        '<div class="col-sm-12 col-md-4 col-lg-3 d-flex my-3 justify-content-center"><div class="card"><div class="card-body"><h5 class="card-title">' + appointmentTitle + '</h5><p class="card-text">' + appointmentDescription + '</p><button class="btn btn-dark" onclick="loadAppointmentDetails(\'' + result[i].app_id + '\')">Show Appointment Details</button></div></div></div>')
                   }
                }
            });
        },
        error: function () {
            console.log("error");

        }
    });

}