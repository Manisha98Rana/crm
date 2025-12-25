const CACHE_NAME = 'formsadda-v4';
const urlsToCache = [
  './',
  './index.php',
  './student/login.php',
  './student/sessions.php',
  './parent/index.php',
  './parent/counsellors.php',
  './parent/student_sessions.php',
  './parent/student_wallet.php',
  './css/spin-style.css',
  './js/main.js',
  './image/favicon.png',
  './manifest.json'
];

self.addEventListener('install', event => {
  // self.skipWaiting(); // Removed to allow manual update
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => self.clients.claim()) // Take control immediately
  );
});
