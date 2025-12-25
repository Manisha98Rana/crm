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
        return Promise.all(
          urlsToCache.map(url => {
            return cache.add(url).catch(error => console.error('Root SW: Failed to cache ' + url, error));
          })
        );
      })
  );
});

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

self.addEventListener('fetch', event => {
  // Skip non-GET requests (like POST)
  if (event.request.method !== 'GET') {
    return;
  }

  // Bypass cache for payments/dynamic URLs with query strings or wallet.php
  if (event.request.url.indexOf('?') !== -1 || event.request.url.indexOf('wallet.php') !== -1) {
    event.respondWith(fetch(event.request));
    return;
  }

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
