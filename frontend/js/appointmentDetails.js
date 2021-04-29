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
function callAppointDateOptionsData(appId) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryDatesByAppId", param: appId },
        dataType: "json",
        success: function (result) {
            //console.log(result);
            for (var i = 0; i < result.length; ++i) {
                $("#allDateOptions").append('<th scope="col">' + result[i].date + '</th>');
                $("#inputRow").append('<td><input class="form-check-input dateSelection" type="checkbox" value="' + result[i].date_id + '"></td>');
                callSingleDateVoteCountData(result[i].date_id);
            }
            ;
            callAppointmentVoteNamesData(appId, result);
        },
        error: function () {
            console.log("error");
        }
    });
}
function callSingleDateVoteCountData(date_id) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryVoteCountByDateId", param: date_id },
        dataType: "json",
        success: function (result) {
            console.log(result);
            $("#voteCount").append('<td>' + result.length + '</td>');
        },
        error: function () {
            console.log("error");
        }
    });
}
function callAppointmentVoteNamesData(app_id, dateCount) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointmentVotes", param: app_id },
        dataType: "json",
        success: function (result) {
            //console.log(result);
            for (var i = 0; i < result.length; ++i) {
                callUserVotes(result[i], app_id, dateCount);
            }
        },
        error: function () {
            console.log("error");
        }
    });
}
function callUserVotes(username, app_id, dateCount) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryUserVotes", param: username, param2: app_id },
        dataType: "json",
        success: function (result) {
            //console.log(dateCount);
            $("tbody").append('<tr id="' + result[0].vote_name + '"><th scope="row">' + result[0].vote_name + '</th></tr>');
            for (var i = 0; i < dateCount.length; ++i) {
                var found = false;
                for (var u = 0; u < result.length; ++u) {
                    if (result[u].date_id == dateCount[i].date_id) {
                        found = true;
                        break;
                    }
                }
                if (found) {
                    $("#" + result[0].vote_name).append('<td><input class="form-check-input" type="checkbox" checked disabled></td>');
                }
                else {
                    $("#" + result[0].vote_name).append('<td><input class="form-check-input" type="checkbox" disabled></td>');
                }
            }
        },
        error: function () {
            console.log("error");
        }
    });
}
function callAppointmentDateVoteData(date_id) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryVotesByDateId", param: date_id },
        dataType: "json",
        success: function (result) {
            // console.log(result);
        },
        error: function () {
            console.log("error");
        }
    });
}
function submitVoteForm() {
    var newVoteDetails = [];
    var username = $("#voteUserName").val();
    var checkBoxes = $(".dateSelection");
    for (var i = 0; i < checkBoxes.length; ++i) {
        if ($(checkBoxes[i]).is(':checked')) {
            insertVote(username, $(checkBoxes[i]).val());
        }
    }
}
function insertVote(username, date_id) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "insertVote", param: username, param2: date_id },
        dataType: "json",
        success: function (result) {
            console.log(result);
            loadAppointmentList();
        },
        error: function () {
            console.log("error");
        }
    });
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
function callAppointmentCommentsData(appId) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryCommentByAppId", param: appId },
        dataType: "json",
        success: function (result) {
            //console.log(result);
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
function deleteAppointment(appId) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "deleteAppointment", param: appId },
        dataType: "json",
        success: function (result) {
            //console.log(result);
            loadAppointmentList();
        },
        error: function () {
            console.log("error");
        }
    });
}
