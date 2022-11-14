import './bootstrap';

import { createApp } from 'vue';
import app from './layouts/app.vue';
import router from './router.js';

createApp(app).use(router).mount("#app");