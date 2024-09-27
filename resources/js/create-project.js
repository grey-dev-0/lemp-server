import {createApp} from "vue";
import common from "./common.js";

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
        add(){
            this.domains.push({name: '', https: true});
        },
        remove(i){
            this.domains.splice(i, 1);
        }
    }
});

common.load(app);
app.mount('#app');