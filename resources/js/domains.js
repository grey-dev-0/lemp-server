import {createApp, getCurrentInstance, ref} from "vue";
import 'bootstrap';
import Datatable from "../vue/datatable/index.js";
import common, {jQuery as $} from "./common.js";

let app = createApp({
    name: 'LempManager',
    setup() {
        let vue = getCurrentInstance();
        let domains = ref(null);
        return {
            domains,
            renderHttps: https => https ? 'Yes' : 'No',
            renderActions(){
                $('[vue-template]').each(function(){
                    $(this).removeClass('vue');
                    common.renderVueTemplate(this, vue, {
                        methods: {
                            remove(e){
                                let dataTable = domains.value.dataTable,
                                    domain = dataTable.row($(e.target).closest('tr')).data();
                                let ansewr = confirm(`Are your sure about removing domain ${domain.name}?`);
                                if(ansewr)
                                    $.get(`/domains/delete/${domain.id}`).then(response => dataTable.draw(), xhr => console.error(`Error deleting domain ${domain.name}`, xhr.responseJSON || xhr.responseText));
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