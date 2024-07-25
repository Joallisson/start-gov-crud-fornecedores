<template>
  <div>
    <div v-if="loading" class="loading-indicator">Carregando...</div>
    <div v-if="error" class="error-message">{{ error }}</div>

    <div class="d-flex mb-3">
      <input
        type="text"
        class="form-control me-2"
        placeholder="Pesquisar por nome ou CPF/CNPJ"
        v-model="searchQuery"
        @keyup.enter="searchProviders"
      />
      <button class="btn btn-primary me-2" @click="searchProviders">Buscar</button>
      <select class="form-select form-select-width-sm me-2" v-model="sortOrder" @change="searchProviders">
        <option value="ASC">Crescente</option>
        <option value="DESC">Decrescente</option>
      </select>
    </div>

    <button class="btn btn-success mb-3" @click="showAddModal">
      <i class="fas fa-plus"></i> Adicionar Fornecedor
    </button>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome da Empresa</th>
          <th>Descrição</th>
          <th>Número do Documento</th>
          <th>Email</th>
          <th>Telefone</th>
          <th>Tipo de Documento</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows" :key="row.id">
          <td>{{ row.id }}</td>
          <td>{{ row.company_name }}</td>
          <td>{{ row.description }}</td>
          <td>{{ row.document_number }}</td>
          <td>{{ row.email }}</td>
          <td>{{ row.phone }}</td>
          <td>{{ row.document_type }}</td>
          <td>
            <button class="btn btn-primary me-2" @click="showAddressModal(row.address)">Ver Endereço</button>
            <button class="btn btn-warning me-2" @click="showEditModal(row)">Editar</button>
            <button class="btn btn-danger" @click="showDeleteModal(row)">Excluir</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center mb-2">
      <div>
        <label for="perPageSelect">Itens por página:</label>
        <select id="perPageSelect" v-model="perPage" @change="updatePerPage" class="form-select">
          <option v-for="option in perPageOptions" :key="option" :value="option">{{ option }}</option>
        </select>
      </div>
      <nav>
        <ul class="pagination mb-0">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <a class="page-link" href="#" @click.prevent="fetchData(currentPage - 1)">Anterior</a>
          </li>
          <li
            v-for="page in totalPages"
            :key="page"
            class="page-item"
            :class="{ active: page === currentPage }"
          >
            <a class="page-link" href="#" @click.prevent="fetchData(page)">{{ page }}</a>
          </li>
          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <a class="page-link" href="#" @click.prevent="fetchData(currentPage + 1)">Próxima</a>
          </li>
        </ul>
      </nav>
    </div>

    <CustomModal
      :visible="isAddressModalVisible"
      title="Endereço"
      :address="selectedAddress"
      @update:visible="isAddressModalVisible = $event"
    />

    <AddProviderModal
      :visible="isAddModalVisible"
      @close="closeAddModal"
      @save="addProvider"
      :errors="errors"
      :general-error="generalError"
    />

    <EditProviderModal
      :visible="isEditModalVisible"
      :provider="selectedProvider"
      @close="closeEditModal"
      @save="editProvider"
      :errors="errors"
      :generalError="generalError"
    />

    <DeleteProviderModal
      :visible="isDeleteModalVisible"
      :provider="selectedProvider"
      @close="closeDeleteModal"
      @confirm="deleteProvider"
    />
  </div>
</template>

<script>
import CustomModal from './CustomModal.vue';
import AddProviderModal from './AddProviderModal.vue';
import EditProviderModal from './EditProviderModal.vue';
import DeleteProviderModal from './DeleteProviderModal.vue';

export default {
  name: 'DataTable',
  components: {
    CustomModal,
    AddProviderModal,
    EditProviderModal,
    DeleteProviderModal
  },
  data() {
    return {
      rows: [],
      selectedAddress: null,
      selectedProvider: null,
      isAddressModalVisible: false,
      isAddModalVisible: false,
      isEditModalVisible: false,
      isDeleteModalVisible: false,
      currentPage: 1,
      perPage: 5,
      perPageOptions: [2, 5, 10, 20, 50, 100],
      totalItems: 0,
      totalPages: 1,
      loading: false,
      error: null,
      errors: {},
      generalError: '',
      provider: this.getInitialProviderData()
    }
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    async fetchData(page = 1) {  
      this.loading = true;
      this.error = null;
      const apiUrl = import.meta.env.VITE_API_BASE_URL;
      console.log(`Fetching data for page: ${page}, perPage: ${this.perPage}, search: ${this.searchQuery}, sort: ${this.sortOrder}`);
      try {
        const response = await fetch(`${apiUrl}/api/provider?per_page=${this.perPage}&page=${page}&search=${this.searchQuery ?? ''}&sort_direction=${this.sortOrder ?? 'desc'}`);
        const data = await response.json();
        console.log('Data fetched:', data);

        this.rows = data.data;
        this.totalItems = data.meta.total_items;
        this.totalPages = data.meta.last_page;
        this.currentPage = data.meta.page;
      } catch (error) {
        this.error = 'Erro ao buscar dados';
        console.error('Erro ao buscar dados:', error);
      } finally {
        this.loading = false;
      }
    },
    updatePerPage() {
      this.fetchData(1); // Recarrega a tabela começando da primeira página com o novo valor de perPage
    },
    searchProviders() {
      this.fetchData(1); // Realiza a busca começando da primeira página
    },
    showAddressModal(address) {
      this.selectedAddress = address;
      this.isAddressModalVisible = true;
    },
    showAddModal() {
      this.isAddModalVisible = true;
      this.errors = {}; // Reset errors when opening the add modal
      this.generalError = ''; // Reset general error when opening the add modal
    },
    closeAddModal() {
      this.isAddModalVisible = false;
      this.resetProviderForm();
    },
    async addProvider(provider) {
      const apiUrl = import.meta.env.VITE_API_BASE_URL;
      try {
        const response = await fetch(`${apiUrl}/api/provider`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(provider)
        });
        if (response.ok) {
          this.fetchData(this.currentPage); // Recarrega os dados após adicionar o fornecedor
          this.closeAddModal();
        } else {
          const errorData = await response.json();
          if (response.status === 422 && errorData.errors) {
            this.errors = errorData.errors;
          } else if (response.status === 400 && errorData.error) {
            this.generalError = errorData.error;
          }
          console.error('Erro ao adicionar fornecedor:', errorData);
        }
      } catch (error) {
        console.error('Erro ao adicionar fornecedor:', error);
      }
    },
    showEditModal(provider) {
      this.selectedProvider = { ...provider }; // Clona o fornecedor para edição
      this.isEditModalVisible = true;
      this.errors = {}; // Reset errors when opening the edit modal
    },
    closeEditModal() {
      this.isEditModalVisible = false;
    },
    async editProvider(provider) {
    const apiUrl = import.meta.env.VITE_API_BASE_URL;
    try {
      const response = await fetch(`${apiUrl}/api/provider/${provider.id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(provider)
      });
      if (response.ok) {
        this.fetchData(this.currentPage);
        this.closeEditModal();
      } else {
        const errorData = await response.json();
        if (response.status === 422 && errorData.errors) {
          this.errors = errorData.errors;
        } else if (response.status === 400 && errorData.error) {
          this.generalError = errorData.error;
        }
        console.error('Erro ao editar fornecedor:', errorData);
      }
    } catch (error) {
      console.error('Erro ao editar fornecedor:', error);
    }
  },
    showDeleteModal(provider) {
      this.selectedProvider = { ...provider }; // Clona o fornecedor para exclusão
      this.isDeleteModalVisible = true;
    },
    closeDeleteModal() {
      this.isDeleteModalVisible = false;
    },
    async deleteProvider(provider) {
      const apiUrl = import.meta.env.VITE_API_BASE_URL;
      try {
        const response = await fetch(`${apiUrl}/api/provider/${provider.id}`, {
          method: 'DELETE',
          headers: {
            'Content-Type': 'application/json'
          }
        });
        if (response.ok) {
          this.fetchData(this.currentPage); // Recarrega os dados após excluir o fornecedor
          this.closeDeleteModal();
        } else {
          console.error('Erro ao excluir fornecedor:', await response.text());
        }
      } catch (error) {
        console.error('Erro ao excluir fornecedor:', error);
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
    }
  }
}
</script>

<style scoped>
.loading-indicator {
  text-align: center;
  font-size: 1.2em;
  margin-bottom: 20px;
}

.error-message {
  color: red;
  text-align: center;
  margin-bottom: 20px;
}

.table {
  margin-bottom: 0; /* Remove espaço inferior da tabela */
}

.pagination,
.form-select {
  margin-top: 10px; /* Adiciona espaço superior para os controles de paginação e seleção */
}
.form-select-width-sm {
  width: 150px; /* Define uma largura específica para o select */
}
</style>
