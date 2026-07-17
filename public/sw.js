// TirtaX Service Worker - Optimized for Offline
const CACHE_NAME = 'tirtax-v2';
const OFFLINE_URL = '/offline';

// Resources to cache on install (pre-cache)
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/manifest.json',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing...');
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[SW] Pre-caching static assets');
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch event - CACHE FIRST strategy (lebih cepat saat offline)
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip API requests (harus selalu online)
    if (event.request.url.includes('/api/') ||
        event.request.url.includes('/midtrans-')) {
        return;
    }

    // Skip admin routes (butuh data real-time)
    if (event.request.url.includes('/admin/')) {
        return;
    }

    // Cache First Strategy - CARI CACHE DULU, baru network
    event.respondWith(
        caches.match(event.request).then((cachedResponse) => {
            // Jika ada di cache, langsung return (CEPAT!)
            if (cachedResponse) {
                console.log('[SW] Cache hit:', event.request.url);

                // Update cache di background (stale-while-revalidate)
                fetch(event.request).then((response) => {
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseClone);
                        });
                    }
                }).catch(() => {
                    // Ignore network errors
                });

                return cachedResponse;
            }

            // Jika tidak ada di cache, fetch dari network
            console.log('[SW] Cache miss, fetching from network:', event.request.url);
            return fetch(event.request)
                .then((response) => {
                    // Cache successful responses
                    if (response && response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return response;
                })
                .catch(() => {
                    // Network failed, show offline page
                    if (event.request.destination === 'document') {
                        return caches.match(OFFLINE_URL);
                    }
                    return new Response('Offline', {
                        status: 503,
                        statusText: 'Service Unavailable'
                    });
                });
        })
    );
});

// Background sync for offline actions
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-shipments') {
        event.waitUntil(syncShipments());
    }
});

function syncShipments() {
    console.log('[SW] Syncing shipments...');
}

// Push notifications
self.addEventListener('push', (event) => {
    const data = event.data ? event.data.json() : {};
    const options = {
        body: data.body || 'Update dari TirtaX',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-96x96.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/'
        }
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'TirtaX', options)
    );
});

// Notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});