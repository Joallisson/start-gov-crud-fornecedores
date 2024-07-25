<template>
    <div v-if="visible" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmar Exclusão</h5>
            <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Você tem certeza que deseja excluir o fornecedor <strong>{{ provider.company_name }}</strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
            <button type="button" class="btn btn-danger" @click="confirmDelete">Excluir</button>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'DeleteProviderModal',
    props: {
      visible: {
        type: Boolean,
        required: true,
      },
      provider: {
        type: Object,
        required: true,
      }
    },
    methods: {
      closeModal() {
        this.$emit('close');
      },
      confirmDelete() {
        this.$emit('confirm', this.provider);
      }
    }
  }
  </script>
  
  <style scoped>
  .modal {
    display: block;
    background: rgba(0, 0, 0, 0.5);
  }
  </style>
  