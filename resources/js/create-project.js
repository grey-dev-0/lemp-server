import {createApp} from "vue";
import common, {jQuery as $} from "./common.js";

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
        },
        submit(){
            $('#submit').trigger('click');
        }
    }
});

common.load(app);
app.mount('#app');