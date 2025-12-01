import {createApp} from "vue";
import common from "./common.js";
import { websocket, wsEvents } from "../vue/log.vue";
import List from "../vue/list/index.js";

let app = createApp({
    name: 'LempManager',
    data() {
        return {
            services: [
                { name: 'dns', status: 'exited', containerName: 'lemp-dns-1', restarting: false },
                { name: 'dns worker', status: 'exited', containerName: 'lemp-dns-1 (worker)', restarting: false },
                { name: 'mariadb', status: 'exited', containerName: 'lemp-mariadb-1', restarting: false },
                { name: 'nginx', status: 'exited', containerName: 'lemp-nginx-1', restarting: false },
                { name: 'php', status: 'exited', containerName: 'lemp-php-1', restarting: false },
                { name: 'phpmyadmin', status: 'exited', containerName: 'lemp-phpmyadmin-1', restarting: false }
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
            websocket.send(JSON.stringify({action: 'STACK_STATUS'}));
        },
        restartService(serviceName) {
            if (!websocket || websocket.readyState !== WebSocket.OPEN) {
                this.error = 'WebSocket connection not ready';
                return;
            }
            const service = this.services.find(s => s.name === serviceName);
            if (service) service.restarting = true;
            websocket.send(JSON.stringify({action: 'RESTART_SERVICE', service: serviceName}));
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
            if (websocket) {
                wsEvents.on('message', (data) => {
                    try {
                        const parsed = JSON.parse(data);
                        if (parsed.type === 'STACK_STATUS') {
                            this.services = parsed.services || [];
                            this.timestamp = parsed.timestamp;
                            this.isLoading = false;
                        } else if (parsed.type === 'SERVICE_RESTARTED') {
                            const service = this.services.find(s => s.name === parsed.service);
                            if (service) service.restarting = false;
                            if (parsed.success) {
                                this.error = null;
                                setTimeout(() => this.fetchStackStatus(), 500);
                            } else {
                                this.error = parsed.error || 'Failed to restart service';
                            }
                        }
                    } catch (_) {}
                });
                wsEvents.on('open', () => {
                    this.fetchStackStatus();
                });
                if (websocket.readyState === WebSocket.OPEN) {
                    this.fetchStackStatus();
                }
            }
        }, 100);
    }
});

common.load(app);
common.loadBundles(app, [List]);
app.mount('#app');
