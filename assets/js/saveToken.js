function saveToken(token) {
    $.ajax({
        url: BASEURL + '/' + LANGUAGE + '/system/notification/save-token',
        type: 'post',
        data: {
            token: token
        },
        success: function (data) {
            console.log(data);
        }
    });
}