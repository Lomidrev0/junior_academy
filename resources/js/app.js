/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import _ from "lodash";

require('./bootstrap');

/**
 * Date-fns library
 */

import {format, formatRelative, formatDistance, isToday, isTomorrow, parseISO} from 'date-fns';
import {sk} from 'date-fns/locale'

export const formatDate = (date, pattern) => {
    return format(date, pattern, {locale: sk});
};

export const formatDateRel = (date) => {
    return formatRelative(date, new Date(), {locale: sk, weekStartsOn: 1});
};

export const formatDateDist = (date) => {
    return formatDistance(date, new Date(), {locale: sk, addSuffix: true}).replace('približne ', '');
};


/**
 * Translations i18n
 */

export const isApp = (appId) => {
    return appId === window.Laravel.appId;
};
const TRANSLATIONS = require('../lang/' + window.Laravel.locale + '.json');
export const i18n = (t, args) => {
    let translation = TRANSLATIONS[t] || t;
    if (args !== undefined) {
        _.forEach(args, (value, key) => {
            translation = _.replace(translation, new RegExp(':' + key, 'g'), value);
        })
        if (args.count !== undefined) {
            const count = args.count;
            const splittedTranslation = translation.split('|');
            splittedTranslation.forEach((part) => {
                var index = part.indexOf(' ');
                var [counter, value] = [part.slice(0, index), part.slice(index + 1)];
                // TODO!!
                if (counter === '{0}' && count === 0
                  || counter === '{1}' && count === 1
                  || counter === '[2,4]' && count >= 2 && count <= 4
                  || counter === '[5,*]' && count >= 5
                ) {
                    translation = value;
                }
            });
        }
    }
    return translation;
};


/**
 * Ziggy routing in js
 */

import ziggyRoute from 'ziggy'
import {Ziggy} from './ziggy';

Ziggy.url = window.Laravel.appUrl;

export const route = (name, params, absolute) => {
    return ziggyRoute(name, params, absolute, Ziggy);
};


/**
 * Import Bootstrap-icons
 */
import {BootstrapVue} from 'bootstrap-vue';
import "bootstrap-icons/font/bootstrap-icons.css"


/**
 * Import Vue
 */
window.Vue = require('vue');
Vue.use(BootstrapVue);
Vue.mixin({
    methods: {
        route,
        i18n,
        formatDate,
        formatDateRel,
        formatDateDist,
        Laravel: (key) => window.Laravel[key],
    },
});

Vue.directive('click-outside', {
    bind: function (el, binding, vnode) {
        el.clickOutsideEvent = function (event) {
            if (!(el == event.target || el.contains(event.target))) {
                vnode.context[binding.expression](event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent)
    },
    unbind: function (el) {
        document.body.removeEventListener('click', el.clickOutsideEvent)
    },
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);


/**
 * Toast
 */

// import Toast from "vue-toastification";
// import "vue-toastification/dist/index.css";
// Vue.use(Toast, {
//     transition: "Vue-Toastification__fade",
//     maxToasts: 3,
//     newestOnTop: true,
//     position: "bottom-center",
//     timeout: 3010,
//     closeOnClick: true,
//     pauseOnFocusLoss: true,
//     pauseOnHover: true,
//     draggable: false,
//     draggablePercent: 1.44,
//     showCloseButtonOnHover: false,
//     hideProgressBar: false,
//     closeButton: false,
//     icon: false,
//     rtl: false
// });

import Notify from 'simple-notify'
import 'simple-notify/dist/simple-notify.min.css'

function createToast( status,title,text,customClass,icon) {
      new Notify({
        status: status,
        title: title,
        text: text,
        effect: 'slide',
        speed: 300,
        customClass: customClass,
        customIcon: icon,
        showIcon: true,
        showCloseButton: true,
        autoclose: true,
        autotimeout: 3000,
        gap: 15,
        distance: 20,
        type: 3,
        position: 'bottom x-center'
    })
}
export const toast = {
    success: (title,text) => {
        createToast('success',title,text,null,'<i class="bi bi-info-circle-fill"></i>');
    },
    error: (title,text) => {
        createToast('error',title,text,null,'<i class="bi bi-exclamation-circle-fill"></i>');
    },
    warning: (title,text) => {
        createToast('warning',title,text,null,'<i class="bi bi-exclamation-triangle-fill"></i>');
    },
}


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const components = {
    'member-list': require('./components/MemberList').default,
    'wswg-editor': require('./components/WswgEditor').default,
    'CourseWrapper': require('./components/Admin/CourseWrapper').default,
    'AddUser': require('./components/Admin/AddUser').default,
    'CourseDetail': require('./components/Admin/CourseDetail').default,
    'CourseList': require('./components/Front/CourseList').default,
    'AlbumWrapper': require('./components/Teacher/AlbumWrapper').default,
    'ImageWrapper': require('./components/Teacher/ImageWrapper').default,
    'InfoCard': require('./components/InfoCard').default,
    'AddArticle': require('./components/Admin/AddArticle').default,
    'Alert': require('./components/Alert').default,
    'Chat': require('./components/Chat').default,
    'TextInput': require('./components/TextInput').default,
    'SarchInput': require('./components/SearchInput').default,
    'MessageModal':require('./components/MessageModal').default,
    'MessageDetail':require('./components/MessageDetail').default,
    'NoResults': require('./components/NoResults').default,
    'InfoModal': require('./components/InfoModal').default,
    'Home': require('./components/Home').default,
};

new Vue({
    el: '#app',
    components
})

/*fancy aps*/

import { Fancybox, Carousel, Panzoom } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox.css";

Fancybox.bind('[data-fancybox="gallery"]', {
    caption: function (fancybox, carousel, slide) {
        return (
          `${slide.caption}`
        );
    },
});


/**
 *
 * local storage
 *
 */


export const getFromLS = (key, dflt=null) => {
    if (_.has(localStorage, key)) {
        let value = localStorage[key];
        if (value === 'null' || value === 'undefined') {
            return dflt;
        } else if (value === 'false') {
            return false;
        }
        return value;
    }
    return dflt;
}

export const saveToLS = (key, value) => {
    localStorage[key] = value;
};

export const removeFromLS = (key) => {
    localStorage.removeItem(key);
};


/**
 *
 * CUSTOM ↓ ↓ ↓ ↓ ↓
 *
 */


/**
 *
 * Show password
 *
 */

$(document).ready(function(){
    $('.showPass').on('click', function(){
        var passInput=$(".pass");
        if(passInput.attr('type')==='password')
        {
            passInput.attr('type','text');
        }else{
            passInput.attr('type','password');
        }
        console.log(passInput);
    })
})

/*toast for reset password*/

export function resetPasToast (session) {
    if(session === 1) {
        toast.error(i18n('New password matches old'),null);
    }
    else if (session === 0) {
        toast.success(i18n('Password has been changed successfully'),null);
    }
    else if(session === 2) {
        toast.error(i18n( 'Incorrect password'),null);
    }
}
window.resetPasToast = resetPasToast;