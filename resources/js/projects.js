import {createApp} from "vue";
import 'bootstrap';
import Datatable from "../vue/datatable/index.js";
import common from "./common.js";

let app = createApp({
    name: 'LempManager',
    methods: {
        renderType: type => projectTypes[type] || type,
        renderActions: function(actions){
            return common.renderVueTemplate(actions, this.$);
        }
    }
}), bundles = [Datatable];

common.load(app);
common.loadBundles(app, bundles);
app.mount('#app');