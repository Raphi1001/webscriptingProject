function callAppointmentDetailsData(appId: number) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryAppointmentById", param: appId },
        dataType: "json",
        success: function (result: any[]) {
            callAppointmentCommentsData(appId);

            $(".card-title").text(result[0].title);
            $(".card-text").text(result[0].description);
        },
        error: function () {
            console.log("error");
        }
    });
}


function callAppointmentCommentsData(appId: number) {
    $.ajax({
        type: "GET",
        url: "../backend/serviceHandler.php",
        cache: false,
        data: { method: "queryCommentByAppId", param: appId },
        dataType: "json",
        success: function (result: any[]) {
            console.log(result);

            for (var i = 0; i < result.length; ++i) {
                $("#comments").append("<p><strong>" + result[i].from_id + ":</strong> " + result[i].comment + "</p>");
            };
        },
        error: function () {
            console.log("error");
        }
    });
}