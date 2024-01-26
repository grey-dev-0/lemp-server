import {createApp, defineAsyncComponent} from "vue";
import 'bootstrap';
import jQuery from "jquery";

import('../vue/navbar').then(bundle => {
    let components = {Card: defineAsyncComponent(() => import('../vue/card.vue'))};
    components = Object.assign(components, bundle.default);
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