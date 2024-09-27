import 'bootstrap';
import navbar from "../vue/navbar"
import jQuery from "jquery";
import {defineAsyncComponent} from "vue";

jQuery.ajaxSettings.headers = {'X-CSRF-TOKEN': jQuery('[name="csrf-token"]').attr('content')};

function load(app){
    navbar.load(app);
    loadComponents(app, {Card: 'card'});
}

function loadBundles(app, bundles){
    bundles.forEach(bundle => {
        bundle.load(app);
    });
}

function loadComponents(app, components){
    for(let name in components)
        app.component(name, defineAsyncComponent(() => import(`../vue/${components[name]}.vue`)));
}

export {jQuery};
export default {load, loadBundles, loadComponents};