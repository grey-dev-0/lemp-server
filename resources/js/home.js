import {createApp} from "vue";
import 'bootstrap';
import jQuery from 'jquery';

let imports = [
    import('../vue/navbar'),
    import('../vue/card.vue')
];

Promise.all(imports).then(bundles => {
    let app = createApp({
        name: 'LempManager',
        components: Object.assign(bundles[0].default, {Card: bundles[1].default}),
        methods: {
            jQuery(){
                return jQuery;
            }
        }
    });

    app.mount('#app');
});