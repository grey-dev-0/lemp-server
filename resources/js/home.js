import {createApp} from "vue";
import common from "./common.js";
import { websocket } from "../vue/log.vue";
import List from "../vue/list/index.js";

let app = createApp({
    name: 'LempManager',
    data() {
        return {
            services: [
                { name: 'dns', status: 'exited', containerName: 'lemp-dns-1' },
                { name: 'dns worker', status: 'exited', containerName: 'lemp-dns-1 (worker)' },
                { name: 'mariadb', status: 'exited', containerName: 'lemp-mariadb-1' },
                { name: 'nginx', status: 'exited', containerName: 'lemp-nginx-1' },
                { name: 'php', status: 'exited', containerName: 'lemp-php-1' },
                { name: 'phpmyadmin', status: 'exited', containerName: 'lemp-phpmyadmin-1' }
            ],
            isLoading: false,
            error: null,
            timestamp: null
        };
    },
    methods: {
        fetchStackStatus() {
            this.isLoading = true;
            this.error = null;
            
            if (!websocket || websocket.readyState !== WebSocket.OPEN) {
                this.error = 'WebSocket connection not ready';
                this.isLoading = false;
                return;
            }
            
            const handler = (e) => {
                try {
                    const data = JSON.parse(e.data);
                    if (data.type === 'STACK_STATUS') {
                        websocket.removeEventListener('message', handler);
                        this.services = data.services || [];
                        this.timestamp = data.timestamp;
                        this.isLoading = false;
                    }
                } catch (err) {
                    // Ignore parsing errors for other messages
                }
            };
            
            websocket.addEventListener('message', handler);
            websocket.send(JSON.stringify({action: 'STACK_STATUS'}));
        },
        getStatusBadgeClass(status) {
            const statusMap = {
                'running': 'bg-success',
                'exited': 'bg-danger',
                'restarting': 'bg-warning',
                'paused': 'bg-secondary'
            };
            return statusMap[status] || 'bg-secondary';
        },
        formatTimestamp(timestamp) {
            return new Date(timestamp).toLocaleString();
        }
    },
    mounted() {
        setTimeout(() => {
            if (websocket && websocket.readyState === WebSocket.OPEN) {
                this.fetchStackStatus();
            }
        }, 100);
    }
});

common.load(app);
common.loadBundles(app, [List]);
app.mount('#app');
