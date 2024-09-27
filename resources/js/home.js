import {createApp} from "vue";
import common from "./common.js";

let app = createApp({
    name: 'LempManager'
});

common.load(app);
app.mount('#app');