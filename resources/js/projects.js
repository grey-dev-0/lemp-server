import {createApp, getCurrentInstance, ref} from "vue";
import 'bootstrap';
import Datatable from "../vue/datatable/index.js";
import common, {jQuery as $} from "./common.js";

let app = createApp({
    name: 'LempManager',
    setup() {
        let vue = getCurrentInstance();
        let projects = ref(null);
        return {
            projects,
            renderType: type => projectTypes[type] || type,
            renderActions() {
                $('[vue-template]').each(function(){
                    $(this).removeClass('vue');
                    common.renderVueTemplate(this, vue, {
                        methods: {
                            remove(e){
                                let dataTable = projects.value.dataTable,
                                    project = dataTable.row($(e.target).closest('tr')).data();
                                let ansewr = confirm(`Are your sure about removing project ${project.path}?`);
                                if(ansewr)
                                    $.get(`/projects/delete/${project.id}`).then(response => dataTable.draw(), xhr => console.error(`Error deleting project ${project.path}`, xhr.responseJSON || xhr.responseText));
                            }
                        }
                    });
                });
            }
        };
    }
}), bundles = [Datatable];

common.load(app);
common.loadBundles(app, bundles);
app.mount('#app');