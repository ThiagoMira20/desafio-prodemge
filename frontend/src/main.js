import { createApp } from 'vue';
import App from './App.vue';
import vuetify from './plugins/vuetify';
import { loadFonts } from './plugins/webfontloader';
import TheMask from './plugins/the-mask';  // Adicione esta linha

loadFonts();

createApp(App)
  .use(vuetify)
  .use(TheMask)  // Adicione esta linha
  .mount('#app');
