/**
 * AJAX calls for communication with the database
 */


//returns the details of the current appointment
function callAppointmentDetailsData(appId: number) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointmentById", param: appId },
        dataType: "json",
        success: function (result: any[]) {
            $("#appointmentTitle").text(result[0].title);
            $("#appointmenCreator").append(result[0].creator_name);
            $("#appointmentLocation").append(result[0].location);
            $("#appointmentDescription").append(result[0].description);
            $("#appointmentExpiryDate").append(result[0].vote_expire);

            //checks if appointment is expired
            var day1: Date = new Date(Date.now());
            var day2: Date = new Date(result[0].vote_expire);
            var difference = day1.getTime() - day2.getTime();

            if (difference > 0) {
                $("#voteForm").hide();
                $("#inputRow").hide();
            }
        },
        error: function () {
            console.log("error");
        }
    });
}

//returns all Date Voting Options of the current appointment
function callAppointDateOptionsData(appId: number) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryDatesByAppId", param: appId },
        dataType: "json",
        success: function (result: any[]) {
            for (var i = 0; i < result.length; ++i) {
                $("#allDateOptions").append('<th scope="col">' + result[i].date + '</th>');
                $("#inputRow").append('<td><input class="form-check-input dateSelection" type="checkbox" value="' + result[i].date_id + '"></td>');

                callSingleDateVoteCountData(result[i].date_id)
            };

            callAppointmentVoteNamesData(appId, result);
        },
        error: function () {
            console.log("error");
        }
    });
}

//returns Vote Count for specific Date of the current appointment
function callSingleDateVoteCountData(date_id: number) {

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryVoteCountByDateId", param: date_id },
        dataType: "json",
        success: function (result: any[]) {
            $("#voteCount").append('<td>' + result.length + '</td>');
        },
        error: function () {
            console.log("error");
        }
    });
}

//returns all Vote Names of the current appointment
function callAppointmentVoteNamesData(app_id: number, date: any) {

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointmentVotes", param: app_id },
        dataType: "json",
        success: function (result: any[]) {
            for (var i = 0; i < result.length; ++i) {
                callUserVotes(result[i], app_id, date);
            }
        },
        error: function () {
            console.log("error");
        }
    });
}

//returns all Votes for specific Name of the current appointment
function callUserVotes(username: string, app_id: number, date: any) {

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryUserVotes", param: username, param2: app_id },
        dataType: "json",
        success: function (result: any[]) {
            $("tbody").append('<tr id="' + result[0].vote_name + '"><th scope="row">' + result[0].vote_name + '</th></tr>');
            for (var i = 0; i < date.length; ++i) {
                var found = false
                for (var u = 0; u < result.length; ++u) {
                    if (result[u].date_id == date[i].date_id) {
                        found = true;
                        break;
                    }
                }
                if (found) {
                    $("#" + result[0].vote_name).append('<td><input class="form-check-input" type="checkbox" checked disabled></td>')
                }
                else {
                    $("#" + result[0].vote_name).append('<td><input class="form-check-input" type="checkbox" disabled></td>')
                }
            }
        },
        error: function () {
            console.log("error");
        }
    });
}

//submit the vote form
function submitVoteForm() {
    var newVoteDetails = [];
    var username = $("#voteUserName").val();
    if (username) {
        if ($('#' + username).length) { //duplicate entries
            alert("A vote with the name " + username + " is already submitted.");
        }
        else {
            var checkBoxes = $(".dateSelection");
            for (var i = 0; i < checkBoxes.length; ++i) {
                if ($(checkBoxes[i]).is(':checked')) {
                    insertVote(username, $(checkBoxes[i]).val()); //call for each checked box
                }
            }
        }
    }
}


//inserts singel vote to current appointment
function insertVote(username: any, date_id: any) {

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "insertVote", param: username, param2: date_id },
        dataType: "json",
        success: function (result: any[]) {

            loadAppointmentList();
        },
        error: function () {
            console.log("error");

        }
    });

}





//takes the information from the commentform and puts it into an array, which will be sent to the backend
function submitNewCommentForm(appId: number) {
    var newCommentDetails = [];
    newCommentDetails[0] = $("#commentAuthor").val();
    newCommentDetails[1] = appId;
    newCommentDetails[2] = $("#commentMessage").val();

    if (!newCommentDetails[0] || !newCommentDetails[1] || !newCommentDetails[2]) {
        return;
    }
    insertComment(newCommentDetails);
}

//inserts singel comment into current appointment
function insertComment(newCommentDetails: any) {

    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "insertComment", param: newCommentDetails },
        dataType: "json",
        success: function (result: any[]) {
            callAppointmentCommentsData(newCommentDetails[1]);
        },
        error: function () {
            console.log("error");

        }
    });
}

//returns all comments for current appointment
function callAppointmentCommentsData(appId: number) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryCommentByAppId", param: appId },
        dataType: "json",
        success: function (result: any[]) {
            $("#comments").empty();
            for (var i = 0; i < result.length; ++i) {
                $("#comments").append("<p><strong>" + result[i].creator_name + ":</strong> " + result[i].comment + "</p>");
            };
        },
        error: function () {
            console.log("error");
        }
    });
}


//deletes an appointment together with all its comments, dates and votes
function deleteAppointment(appId: number) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "deleteAppointment", param: appId },
        dataType: "json",
        success: function (result: any[]) {
            loadAppointmentList();
        },
        error: function () {
            console.log("error");
        }
    });
}


