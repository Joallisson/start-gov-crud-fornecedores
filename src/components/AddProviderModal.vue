<template>
  <div v-if="visible" class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Adicionar Fornecedor</h5>
          <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveProvider">
            <div class="mb-3">
              <label class="form-label">Nome da Empresa</label>
              <input type="text" class="form-control" v-model="provider.company_name" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Descrição</label>
              <input type="text" class="form-control" v-model="provider.description" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" v-model="provider.email" required />
              <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Telefone</label>
              <input type="text" class="form-control" v-model="provider.phone" @input="validatePhone" required />
              <div v-if="errors.phone" class="text-danger">{{ errors.phone }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Tipo de Documento</label>
              <select class="form-select" v-model="provider.document_type" @change="validateDocumentNumber" required>
                <option value="CPF">CPF</option>
                <option value="CNPJ">CNPJ</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Número do Documento</label>
              <input type="text" class="form-control" v-model="provider.document_number" @input="validateDocumentNumber" required />
              <div v-if="errors.document_number" class="text-danger">{{ errors.document_number }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label">Endereço</label>
              <input type="text" class="form-control" v-model="provider.address.street" placeholder="Rua" required />
              <input type="text" class="form-control mt-2" v-model="provider.address.city" placeholder="Cidade" required />
              <input type="text" class="form-control mt-2" v-model="provider.address.number" placeholder="Número" required />
              <input type="text" class="form-control mt-2" v-model="provider.address.state" placeholder="Estado" required />
              <input type="text" class="form-control mt-2" v-model="provider.address.zip_code" placeholder="CEP" required />
              <input type="text" class="form-control mt-2" v-model="provider.address.country" placeholder="País" required />
              <input type="text" class="form-control mt-2" v-model="provider.address.neighborhood" placeholder="Bairro" required />
            </div>
            <div v-if="generalError" class="text-danger">{{ generalError }}</div>
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
  name: 'AddProviderModal',
  props: {
    visible: {
      type: Boolean,
      required: true,
    },
    errors: {
      type: Object,
      default: () => ({})
    },
    generalError: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      provider: this.getInitialProviderData()
    };
  },
  methods: {
    closeModal() {
      this.$emit('close');
      this.resetProviderForm();
    },
    saveProvider() {
      if (this.validateForm()) {
        this.$emit('save', this.provider);
      }
    },
    resetProviderForm() {
      this.provider = this.getInitialProviderData();
    },
    getInitialProviderData() {
      return {
        company_name: '',
        description: '',
        email: '',
        phone: '',
        document_type: 'CPF',
        document_number: '',
        address: {
          street: '',
          city: '',
          number: '',
          state: '',
          zip_code: '',
          country: '',
          neighborhood: ''
        }
      };
    },
    validatePhone() {
      const phone = this.provider.phone.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
      if (phone.length >= 8 && phone.length <= 11) {
        this.errors.phone = null;
      } else {
        this.errors.phone = 'O telefone deve ter entre 8 e 11 dígitos.';
      }
      this.provider.phone = phone;
    },
    validateDocumentNumber() {
      const documentNumber = this.provider.document_number.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
      if (this.provider.document_type === 'CPF') {
        if (documentNumber.length === 11) {
          this.errors.document_number = null;
        } else {
          this.errors.document_number = 'O CPF deve ter 11 dígitos.';
        }
      } else if (this.provider.document_type === 'CNPJ') {
        if (documentNumber.length === 14) {
          this.errors.document_number = null;
        } else {
          this.errors.document_number = 'O CNPJ deve ter 14 dígitos.';
        }
      }
      this.provider.document_number = documentNumber;
    },
    validateForm() {
      this.validatePhone();
      this.validateDocumentNumber();
      return !this.errors.phone && !this.errors.document_number;
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
