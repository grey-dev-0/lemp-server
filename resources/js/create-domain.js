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
    }
});

common.load(app);
common.loadComponents(app, {CodeEditor: 'code-editor'});
app.mount('#app');