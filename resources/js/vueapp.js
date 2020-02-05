import Vue from 'vue'
import VueRouter from 'vue-router'
import { ModalPlugin } from 'bootstrap-vue'
import { FormInputPlugin } from 'bootstrap-vue'
import { FormSelectPlugin } from 'bootstrap-vue'
import { DropdownPlugin } from 'bootstrap-vue'
import Vuelidate from 'vuelidate'
window.axios = require('axios');

Vue.use(VueRouter);
Vue.use(ModalPlugin);
Vue.use(FormInputPlugin);
Vue.use(FormSelectPlugin);
Vue.use(DropdownPlugin);
Vue.use(Vuelidate);

import App from './components/AppComponent'
import Main from './components/MainComponent'
import Departments from './components/DepartmentsComponent'
import Employees from './components/EmployeesComponent'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'main',
            component: Main,
            props: { title: "Main" }
        },
        {
            path: '/departments',
            name: 'departments',
            component: Departments,
            props: {
                title: "Departments",
            }
        },
        {
            path: '/employees',
            name: 'employees',
            component: Employees,
            props: {
                title: "Employees",
            }
        },
    ],
});
const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
