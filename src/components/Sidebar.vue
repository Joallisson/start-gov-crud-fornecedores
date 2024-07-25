<template>
    <div class="sidebar">
      <b-list-group>
        <b-list-group-item href="#">Fornecedores</b-list-group-item>
      </b-list-group>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Sidebar'
  }
  </script>
  
  <style scoped>
  .sidebar {
    width: 250px;
    height: 100vh;
    background-color: #f8f9fa;
    border-right: 1px solid #ddd;
    padding: 1rem;
  }
  </style>
  