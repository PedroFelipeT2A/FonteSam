self.addEventListener('activate', function(event) {

});

self.addEventListener('fetch', function(event) {

});

self.addEventListener('message', function(event) {
  if (event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
});

self.addEventListener('notificationclick', function(event) {
  event.notification.close();
    if (event.notification.data.url) {
    event.waitUntil(
      clients.openWindow(event.notification.data.url)
    );
  }
});