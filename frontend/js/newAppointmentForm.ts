var dateCount = 1;

function addDate() {
    ++dateCount;
    var newItem = '<div class="mb-1"><input type="date" class="form-control appointmentDateOption" id="appointmentDateOption-' + dateCount + '"></div>';
    $(newItem).hide().appendTo("#appointmentDateOptionGroup").slideDown(500); //adds new item to list
}

function submitNewAppointmentForm() {
    var newAppointmentDetails = [];
    newAppointmentDetails[0] = $("#appointmentTitle").val();
    newAppointmentDetails[1] = $("#appointmentLocation").val();
    newAppointmentDetails[2] = $("#appointmentDescription").val();
    newAppointmentDetails[3] = $("#appointmentExpiryDate").val();
    newAppointmentDetails[4] = $("#appointmentAuthor").val();
    
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
    newAppointmentDetails[5] = appointmentDateOptionsArr;
    if (!newAppointmentDetails[0] || !newAppointmentDetails[1] || !newAppointmentDetails[2] || !newAppointmentDetails[3] || !newAppointmentDetails[4] || !newAppointmentDetails[5][0]) {
        return;
    }
    insertAppointment(newAppointmentDetails)
}

function insertAppointment(newAppointmentDetails: any) {

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "insertAppointment", param: newAppointmentDetails },
        dataType: "json",
        success: function (result: any[]) {
            alert(result);

        },
        error: function () {
            console.log("error");

        }
    });
}