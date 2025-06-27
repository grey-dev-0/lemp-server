import 'bootstrap';
import navbar from "../vue/navbar"
import jQuery from "jquery";
import {defineAsyncComponent, h, compile, render} from "vue";
import fontawesome from "./fontawesome";

jQuery.ajaxSettings.headers = {'X-CSRF-TOKEN': jQuery('[name="csrf-token"]').attr('content')};

function load(app){
    navbar.load(app);
    fontawesome.load(app);
    loadComponents(app, {Card: 'card', Log: 'log', Icon: 'icon'});
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

function renderVueTemplate(template, app, options = {}) {
    const compiled = compile(template.innerHTML);
    const node = h({render: compiled, ...options});
    node.appContext = app.appContext;
    render(node, template.parentNode);
    template.remove();
}

export {jQuery};
export default {load, loadBundles, loadComponents, renderVueTemplate};