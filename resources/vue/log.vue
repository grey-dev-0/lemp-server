<template>
<div id="log-container" class="position-absolute w-100 bg-black" v-if="open">
    <div class="w-100 h-100 position-relative">
        <span id="close-log" class="position-absolute font-weight-bolder text-white mr-4" @click="toggle">&times;</span>
        <pre class="h-100">
            <code class="text-white">
<span v-for="(line, i) in lines" :key="i">{{line}}</span>
            </code>
        </pre>
    </div>
</div>
</template>

<script>
let ws, ping;

export { ws as websocket };

export default {
    name: 'Log',
    data: () => ({
        open: false,
        lines: []
    }),
    methods: {
        init(){
            ws = new WebSocket('wss://lemp.docker/log');
            ws.onmessage = e => {
                if(e.data && e.data != 'PONG')
                    this.lines.push(e.data);
                if(this.lines.length > 256)
                    this.lines.splice(0, this.lines.length - 256);
            };
            ws.onopen = () => {
                ws.send(JSON.stringify({action: 'LOG'}));
                ping = setInterval(() => {
                    ws.send(JSON.stringify({action: 'PING'}));
                }, 15000);
            };
            ws.onclose = () => {
                clearInterval(ping);
                setTimeout(() => this.init(), 5000);
            }
        },
        toggle(){
            this.open = !this.open;
        }
    },
    created(){
        this.init();
    }
}
</script>

<style lang="scss">
#log-container{
    z-index: 100;
    bottom: 0;
    height: 25vh;

    #close-log{
        cursor: pointer;
        font-size: 2rem;
        top: 0;
        right: 0;
    }

    pre{
        overflow: auto;
    }
}
</style>