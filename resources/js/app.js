/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./components-tags');
import router from './routes';
window.Vue = require('vue');
import Vue from 'vue';
import Gate from './Gate'
Vue.prototype.$gate = new Gate(window.user);

/**
 * vform
 */
import { Form, HasError, AlertError } from 'vform';

window.Form=Form;
Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

require('./bootstrap');

Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * Vue filters
 */
Vue.filter('firstUp',function (text) {
    return text.charAt(0).toUpperCase() + text.slice(1)
});

/**
 * moment.js filter
 */
import moment from 'moment';
Vue.filter('dateTime',function (date) {
    return moment(date).format('MMMM Do YYYY, h:mm:ss a');
});

/**
 * Vue progressbar
 */
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
});

/**
 * SweetAlert 2
 */
// ES6 Modules or TypeScript
import Swal from 'sweetalert2';
window.Swal=Swal;
//toaster notification
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.Toast=Toast;

let Fire = new Vue();
window.Fire = Fire;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


//Vue.component('notfound', require('./components/Notfound.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

new Vue({
    el: "#app",
    router: router,
    data:{
        search : ''
    },
    methods:{
        searchit:_.debounce(()=>{
            Fire.$emit('searching');
            //console.log('searching');
        },1000)
    }
});

