import {library} from '@fortawesome/fontawesome-svg-core';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {
    faPlus,
    faFolderPlus,
    faPenToSquare,
    faArrowsRotate
} from '@fortawesome/free-solid-svg-icons';
import {faTrashAlt} from '@fortawesome/free-regular-svg-icons';

library.add(
    faPlus,
    faFolderPlus,
    faPenToSquare,
    faTrashAlt,
    faArrowsRotate
);

function load(app){
    app.component('FontAwesomeIcon', FontAwesomeIcon);
}

export default {load};
