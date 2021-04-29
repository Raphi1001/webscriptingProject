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
function callAppointDatesData(appId) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryDatesByAppId", param: appId },
        dataType: "json",
        success: function (result) {
            for (var i = 0; i < result.length; ++i) {
                $("#allDateOptions").append('<th scope="col">' + result[i].date + '</th>');
                callAppointmentDateVoteData(result[i].date_id);
            }
            ;
        },
        error: function () {
            console.log("error");
        }
    });
}
function callAppointmentDateVoteData(date_id) {
    for (var i = 0; i < 3; ++i) {
        $("#allDateOptions").append('<th scope="col">25.0' + i + '.1998</th>');
        $("#voteCount").append('<td>' + 3 * i + '</td>');
    }
    $("tbody").append('<tr><th scope="row">Georg</th><td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" disabled></td><td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" disabled></td><td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" disabled></td></tr>');
    $("tbody").append('<tr><th scope="row">Harty</th><td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" disabled></td><td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" disabled></td><td><input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" disabled></td></tr>');
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
