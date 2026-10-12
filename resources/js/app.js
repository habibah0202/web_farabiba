

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('notificationMenu', (ids, key) => ({
    notifOpen: false,
    unreadIds: ids,
    init() {
        try {
            const read = JSON.parse(localStorage.getItem(key) || '[]');
            this.unreadIds = ids.filter(id => !read.includes(id));
        } catch {}
    },
    get unreadCount() { return this.unreadIds.length; },
    toggle() {
        this.notifOpen = !this.notifOpen;
        if (!this.notifOpen) return;
        try {
            const read = JSON.parse(localStorage.getItem(key) || '[]');
            localStorage.setItem(key, JSON.stringify([...new Set([...read, ...ids])].slice(-100)));
        } catch {}
        this.unreadIds = [];
    },
}));
Alpine.start();
