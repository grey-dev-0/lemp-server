import {createApp} from "vue";
import common, {jQuery as $} from "./common.js";
import { websocket, wsEvents } from "../vue/log.vue";

let app = createApp({
    name: 'LempManager',
    data(){
        return {
            domains: [
                {name: '', https: true}
            ]
        };
    },
    methods: {
        loadNginxConfig(){
            const domain = $('#domain').val();
            if(domain && websocket && websocket.readyState === WebSocket.OPEN){
                websocket.send(JSON.stringify({
                    action: 'GET_NGINX_CONFIG',
                    domain: domain.trim()
                }));
            }
        },
        generateStub(){
            let editor = this.$refs.configEditor;
            let proceed = editor.code.length == 0 || confirm('Currently written configuration will be overwritten with the stub, continue?');
            if(!proceed)
                return;
            let request = {}, domain = $('#domain').val();
            if(domain.length)
                request = {domain};
            $.post('/stub/' + $('#project').val(), request, response => {
                editor.setContent(response);
            });
        },
        submit(){
            $('#submit').trigger('click');
        }
    },
    mounted(){
        setTimeout(() => {
            if(websocket){
                wsEvents.on('message', (data) => {
                    try{
                        const parsed = JSON.parse(data);
                        if(parsed.type === 'NGINX_CONFIG' && parsed.success){
                            this.$refs.configEditor.setContent(parsed.content);
                        }
                    }catch(_){}
                });
                wsEvents.on('open', () => {
                    this.loadNginxConfig();
                });
                if(websocket.readyState === WebSocket.OPEN){
                    this.loadNginxConfig();
                }
            }
        }, 100);
    }
});

common.load(app);
common.loadComponents(app, {CodeEditor: 'code-editor'});
app.mount('#app');
