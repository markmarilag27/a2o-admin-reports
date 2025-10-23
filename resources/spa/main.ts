import { createApp } from 'vue';
import router from './router';
import App from './App.vue';

const element = document.getElementById('app');

if (element) {
  const app = createApp(App);

  app.use(router);

  router.isReady().then(() => app.mount(element))
}
