const notificationSound = new Audio('/storage/sounds/notification.mp3');

function onNotify(payload) {
    getNotificationsMiniList();

    if (notificationSound) {
        notificationSound.play();
    }
}