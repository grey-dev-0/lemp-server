import {createApp, defineAsyncComponent} from "vue";
import 'bootstrap';
import jQuery from 'jquery';

let imports = [
    import('../vue/navbar'),
    import('../vue/datatable')
];

Promise.all(imports).then(bundles => {
    let components = {};
    for(let i in bundles)
        components = Object.assign(components, bundles[i].default);
    components.Card = defineAsyncComponent(() => import('../vue/card.vue'));
    let app = createApp({
        name: 'LempManager',
        components,
        methods: {
            jQuery(){
                return jQuery;
            }
        }
    });

    app.mount('#app');
});