import {createApp, defineAsyncComponent} from "vue";
import 'bootstrap';
import jQuery from "jquery";

import('../vue/navbar').then(bundle => {
    let components = {Card: defineAsyncComponent(() => import('../vue/card.vue'))};
    components = Object.assign(components, bundle.default);
    let app = createApp({
        name: 'LempManager',
        components,
        data(){
            return {
                domains: [
                    {name: '', https: true}
                ]
            };
        },
        methods: {
            jQuery(){
                return jQuery;
            },
            add(){
                this.domains.push({name: '', https: true});
            },
            remove(i){
                this.domains.splice(i, 1);
            }
        }
    });

    app.mount('#app');
});