<template>
    <div v-if="visible" class="modal-overlay" @click.self="hideModal">
      <div class="modal-content">
        <h3>{{ title }}</h3>
        <div v-if="address">
          <p><strong>Rua:</strong> {{ address.street }}</p>
          <p><strong>Cidade:</strong> {{ address.city }}</p>
          <p><strong>Número:</strong> {{ address.number }}</p>
          <p><strong>Estado:</strong> {{ address.state }}</p>
          <p><strong>CEP:</strong> {{ address.zip_code }}</p>
          <p><strong>País:</strong> {{ address.country }}</p>
          <p><strong>Bairro:</strong> {{ address.neighborhood }}</p>
        </div>
        <button class="close-button" @click="hideModal">OK</button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'CustomModal',
    props: {
      visible: {
        type: Boolean,
        required: true,
      },
      title: {
        type: String,
        required: true,
      },
      address: {
        type: Object,
        required: false,
        default: () => ({}),
      }
    },
    watch: {
      visible(newVal) {
        if (newVal) {
          this.onOpen();
        }
      }
    },
    methods: {
      hideModal() {
        this.$emit('update:visible', false);
      },
      onOpen() {
        console.log('Modal foi aberto');
      }
    },
  };
  </script>
  
  <style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: left;
    width: 400px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .close-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
    display: block;
    width: 100%;
  }
  
  .close-button:hover {
    background-color: #0056b3;
  }
  </style>
  