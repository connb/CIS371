import Vue from 'vue'
import './plugins/vuetify'
import App from './App.vue'
import store from './store'
import VueRouter from 'vue-router'
import Account from './components/Account.vue'
import MainTable from './components/MainTable.vue'
require('./assets/styles.css')

Vue.config.productionTip = false
Vue.use(VueRouter)

const appRoutes = [
  {path: '/', component: MainTable},
  {path: '/Account', component: Account},
];

export const appRouter = new VueRouter({
  routes: appRoutes
});

new Vue({
  store,
  router: appRouter,
  render: h => h(App)
}).$mount('#app')