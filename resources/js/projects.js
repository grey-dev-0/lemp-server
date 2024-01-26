import {createApp, defineAsyncComponent} from "vue";
import 'bootstrap';
import jQuery from 'jquery';

let imports = [
    import('../vue/navbar'),
    import('../vue/datatable')
];

Promise.all(imports).then(bundles => {
    let components = {Card: defineAsyncComponent(() => import('../vue/card.vue'))};
    for(let i in bundles)
        components = Object.assign(components, bundles[i].default);
    let app = createApp({
        name: 'LempManager',
        components,
        methods: {
            jQuery(){
                return jQuery;
            },
            renderType:(type) => type
        },
        created(){
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                }
            })
        }
    });

    app.mount('#app');
});