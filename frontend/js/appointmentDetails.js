"use strict";
function callAppointmentDetailsData(appId) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointmentById", param: appId },
        dataType: "json",
        success: function (result) {
            $("#appointmentTitle").text(result[0].title);
            $("#appointmenCreator").append(result[0].creator_name);
            $("#appointmentLocation").append(result[0].location);
            $("#appointmentDescription").append(result[0].description);
        },
        error: function () {
            console.log("error");
        }
    });
}
function callAppointmentCommentsData(appId) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryCommentByAppId", param: appId },
        dataType: "json",
        success: function (result) {
            console.log(result);
            $("#comments").empty();
            for (var i = 0; i < result.length; ++i) {
                $("#comments").append("<p><strong>" + result[i].creator_name + ":</strong> " + result[i].comment + "</p>");
            }
            ;
        },
        error: function () {
            console.log("error");
        }
    });
}
function callAppointmentDateVoteData(appId) {
    for (var i = 0; i < 10; ++i) {
        var date = "0" + i + ".04.2021";
        var voteCount = 3 * i;
        $("#allDateOptions").append('<div class="col-sm-12 col-md-4 col-lg-3 d-flex my-3 justify-content-center"><div class="card text-center"><div class="card-body"><h5 class="card-title">Date: ' + date + '</h5><button class="btn btn-dark">Vote</button><p class="card-text"><strong>Votes: </strong>' + voteCount + '</p><ul id="votes' + i + '" class="text-start"></ul></div></div></div>');
        for (var u = 0; u < 4; ++u) {
            $("#votes" + i).append('<li>Harry</li>');
        }
    }
    ;
}
function submitNewCommentForm(appId) {
    var newCommentDetails = [];
    newCommentDetails[0] = $("#commentAuthor").val();
    newCommentDetails[1] = appId;
    newCommentDetails[2] = $("#commentMessage").val();
    if (!newCommentDetails[0] || !newCommentDetails[1] || !newCommentDetails[2]) {
        return;
    }
    insertComment(newCommentDetails);
}
function insertComment(newCommentDetails) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "insertComment", param: newCommentDetails },
        dataType: "json",
        success: function (result) {
            callAppointmentCommentsData(newCommentDetails[1]);
        },
        error: function () {
            console.log("error");
        }
    });
}
