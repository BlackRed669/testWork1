import './bootstrap';
import { createApp } from 'vue';
import department_table from './components/department-table.vue';
import department_edit from './components/department-edit.vue';
import worker_table from './components/worker-table.vue';
import worker_edit from './components/worker-edit.vue';

const app = createApp({
  components: {
      'department-table': department_table,
      'department-edit': department_edit,
      'worker-table': worker_table,
      'worker-edit': worker_edit,
  }
});

app.mount('#app');


