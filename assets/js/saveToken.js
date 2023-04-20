function saveToken(token) {
    $.ajax({
        url: '/system/notification/save-token',
        type: 'post',
        data: {
            token: token
        }
    });
}