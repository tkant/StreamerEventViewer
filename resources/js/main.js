import Vue from 'vue';
import router from "./router";
import App from './App.vue';
import BootstrapVue from 'bootstrap-vue';

Vue.use(BootstrapVue);
Vue.use(router);

new Vue({
  router,
  render: h => h(App)
}).$mount('#app');