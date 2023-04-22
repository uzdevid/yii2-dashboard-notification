function getNotificationsMiniList() {
    $.get(BASEURL + '/' + LANGUAGE + "/system/notification/mini-list", function (data) {
        $("#notifications-badge").html(data.body.badge);

        if (data.body.badge == 0) {
            $("#notifications-badge").hide();
        } else {
            $("#notifications-badge").show();
        }

        $('#notifications-mini-list').html(data.body.view);
    });
}