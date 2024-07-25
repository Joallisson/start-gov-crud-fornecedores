<template>
  <div v-if="visible" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Editar Fornecedor</h5>
          <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveProvider">
            <div class="mb-3">
              <label class="form-label">Nome da Empresa</label>
              <input type="text" class="form-control" v-model="localProvider.company_name" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Descrição</label>
              <input type="text" class="form-control" v-model="localProvider.description" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" v-model="localProvider.email" required />
              <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Telefone</label>
              <input type="text" class="form-control" v-model="localProvider.phone" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Tipo de Documento</label>
              <select class="form-select" v-model="localProvider.document_type" required>
                <option value="CPF">CPF</option>
                <option value="CNPJ">CNPJ</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Número do Documento</label>
              <input type="text" class="form-control" v-model="localProvider.document_number" required />
              <div v-if="errors.document_number" class="text-danger">{{ errors.document_number[0] }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Endereço</label>
              <input type="text" class="form-control" v-model="localProvider.address.street" placeholder="Rua" required />
              <input type="text" class="form-control mt-2" v-model="localProvider.address.city" placeholder="Cidade" required />
              <input type="text" class="form-control mt-2" v-model="localProvider.address.number" placeholder="Número" required />
              <input type="text" class="form-control mt-2" v-model="localProvider.address.state" placeholder="Estado" required />
              <input type="text" class="form-control mt-2" v-model="localProvider.address.zip_code" placeholder="CEP" required />
              <input type="text" class="form-control mt-2" v-model="localProvider.address.country" placeholder="País" required />
              <input type="text" class="form-control mt-2" v-model="localProvider.address.neighborhood" placeholder="Bairro" required />
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EditProviderModal',
  props: {
    visible: {
      type: Boolean,
      required: true,
    },
    provider: {
      type: Object,
      required: true,
    },
    errors: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      localProvider: { ...this.provider }
    };
  },
  watch: {
    provider: {
      immediate: true,
      handler(newVal) {
        this.localProvider = { ...newVal };
      }
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    saveProvider() {
      this.$emit('save', this.localProvider);
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
