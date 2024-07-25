<template>
  <div id="app">
    <Navbar />
    <div class="d-flex">
      <Sidebar />
      <div class="flex-fill p-3">
        <DataTable />
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue';
import Sidebar from './components/Sidebar.vue';
import DataTable from './components/DataTable.vue';

export default {
  name: 'App',
  components: {
    Navbar,
    Sidebar,
    DataTable
  }
}
</script>

<style>
.d-flex {
  display: flex;
}
.flex-fill {
  flex: 1;
}
.p-3 {
  padding: 1rem;
}
</style>
