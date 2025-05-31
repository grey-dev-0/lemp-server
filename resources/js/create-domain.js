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
        submit(){
            $('#submit').trigger('click');
        }
    }
});

common.load(app);
common.loadComponents(app, {CodeEditor: 'code-editor'});
app.mount('#app');