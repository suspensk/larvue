
// /**
//  * First we will load all of this project's JavaScript dependencies which
//  * includes Vue and other libraries. It is a great starting point when
//  * building robust, powerful web applications using Vue and Laravel.
//  */

// require('./bootstrap');

// window.Vue = require('vue');

// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));

// const app = new Vue({
//     el: '#app'
// });



import Vue from 'vue'
import VueRouter from 'vue-router'
import VueJWT from 'vuejs-jwt'
import Notifications from 'vue-notification'

Vue.use(VueJWT, {keyName : 'jwt'});
Vue.use(VueRouter);
Vue.use(Notifications);

import App from './views/App'
import Dashboard from './views/Board'
import Login from './views/Login'
import Register from './views/Register'
import Home from './views/Welcome'
import NewNote from './views/NewNote'
import noteList from './views/noteList'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
        },
        {
            path: '/board',
            name: 'board',
            component: Dashboard,
        },
        {
            path: '/newnote',
            name: 'newnote',
            component: NewNote,
        },
        {
            path: '/notes',
            name: 'notelist',
            component: noteList,
        },
    ],
});
Vue.component('button-counter', {
    data: function () {
        return {
            count: 0
        }
    },
    template: '<button v-on:click="count++">Счётчик кликов — {{ count }}</button>'
})

// Vue.component('modal', {
//     template: '#modal-template'
// })

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
