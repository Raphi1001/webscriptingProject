var dateCount = 1;

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