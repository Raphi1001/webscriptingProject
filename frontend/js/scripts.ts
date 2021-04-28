var dateCount = 1;
function loadData() {

}

function loadAppointmentList() {
   $.ajax({
      type: "GET",
      url: "../backend/serviceHandler.php",
      cache: false,
      data: { method: "queryAppointment" },
      dataType: "json",
      success: function (result: any[]) {
         $("#pageContent").load("templates/appointmentList.html", function () {
            console.log(result);

            for (var i = 0; i < result.length; ++i) {
               var appointmentTitle = result[i].title;
               var appointmentDescription = result[i].description;
               $("#allAppointments").append(
                  '<div class="col-sm-12 col-md-4 col-lg-3 d-flex my-3 justify-content-center"><div class="card"><div class="card-body"><h5 class="card-title">' + appointmentTitle + '</h5><p class="card-text">' + appointmentDescription + '</p><button class="btn btn-dark" onclick="loadAppointmentDetails(\' ' + appointmentTitle + '\')">Show Appointment Details</button></div></div></div>')
            }
         });
      },
      error: function () {
         console.log("error");

      }
   });

}

function loadNewAppointmentForm() {
   $("#pageContent").load("templates/newAppointmentForm.html");
}

function loadAppointmentDetails(appointmentTitle: string) {
   $.ajax({
      type: "GET",
      url: "../backend/serviceHandler.php",
      cache: false,
      data: { method: "queryAppointmentByTitle", param: "Test" },
      dataType: "json",
      success: function (result: any[]) {
         $("#pageContent").load("templates/appointmentList.html", function () {
            console.log(result[0]);

            $("#pageContent").load("templates/appointmentDetails.html", function () {


               var appointmentDescription = "Diese Beschreibung dient Testzwecken und muss überschrieben werden";
               var appointmentCommentsAuthors: string[] = ["Harry", "Tribun"];
               var appointmentCommentsText: string[] = ["Soo cool", "nice"];



               $(".card-title").text(result[0].title);
               $(".card-text").text(result[0].description);


               for (var i = 0; i < appointmentCommentsAuthors.length && i < appointmentCommentsText.length; ++i) {
                  $(".card-footer").append("<p><strong>" + appointmentCommentsAuthors[i] + ":</strong> " + appointmentCommentsText[i] + "</p>");
               };
            });


         });
      },
      error: function () {
         console.log("error");

      }
   });

}




function addDate() {
   ++dateCount;

   var newItem = '<div class="mb-1"><input type="date" class="form-control appointmentDateOption" id="appointmentDateOption-' + dateCount + '"></div>';

   $(newItem).hide().appendTo("#appointmentDateOptionGroup").slideDown(500); //adds new item to list
}


function submitNewAppointmentForm() {

   var appointmentTitle = $("#appointmentTitle").val();

   var appointmentLocation = $("#appointmentLocation").val();
   var appointmentDescription = $("#appointmentDescription").val();
   var appointmentExpiryDate = $("#appointmentExpiryDate").val();


   var appointmentDateOptions = $(".appointmentDateOption")
   var appointmentDateOptionsArr = [];
   var found = false;


   for (var i = 0; i < appointmentDateOptions.length; ++i) {
      var currentDateOption: HTMLElement = $(".appointmentDateOption")[i]; //returns html element
      var current: JQuery<HTMLElement> = $(currentDateOption); //convert html element back to jqerry object
      if (current.val()) {
         found = true;
         appointmentDateOptionsArr.push(current.val());
      }
   }
   if (!found) {
      $("#appointmentDateOptionGroup").addClass("error");
      return;
   }

   $("#appointmentDateOptionGroup").removeClass("error");

   if (!appointmentTitle || !appointmentLocation || !appointmentDescription || !appointmentExpiryDate || !appointmentDateOptionsArr[0]) {
      return;
   }



   //use these for database input
   alert("sucess!");
   console.log(appointmentTitle);
   console.log(appointmentLocation);
   console.log(appointmentDescription);
   console.log(appointmentExpiryDate);
   console.log(appointmentDateOptionsArr);
}

