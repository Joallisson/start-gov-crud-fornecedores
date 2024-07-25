
# Painel de Integração com API de Fornecedores

Este projeto é um painel desenvolvido em Vue.js para integração com a API de fornecedores. Ele possui funcionalidades simples para cadastrar, listar, editar, deletar e filtrar fornecedores na tabela.

## Requisitos

Antes de começar, certifique-se de ter as seguintes ferramentas instaladas:

- [Node.js](https://nodejs.org/en/download/) (v18.12.0 ou superior)
- [Yarn](https://classic.yarnpkg.com/en/docs/install/#debian-stable) (v1.22.19 ou superior)
- [Git](https://git-scm.com/)

## Instalação

Siga as etapas abaixo para configurar o ambiente de desenvolvimento:

1. Clone o repositório:
   ```bash
   git clone https://github.com/Joallisson/start-gov-crud-fornecedores.git
   cd start-gov-crud-fornecedores
   ```

2. Entre na branch do projeto:
   ```bash
   git checkout painel-fornecedores-vue
   ```

3. Instale as dependências:
   ```bash
   yarn install
   ```

4. Inicie o servidor de desenvolvimento:
   ```bash
   yarn dev
   ```

5. Copie a URL do projeto que apareceu no terminal e cole no navegador para acessar a aplicação.

## Funcionalidades

- **Cadastrar Fornecedor:** Adicione um novo fornecedor preenchendo o formulário no modal de cadastro.
- **Listar Fornecedores:** Veja todos os fornecedores cadastrados em uma tabela paginada.
- **Editar Fornecedor:** Atualize os dados de um fornecedor existente.
- **Deletar Fornecedor:** Remova um fornecedor do sistema.
- **Filtrar Fornecedores:** Utilize a barra de pesquisa para filtrar fornecedores pelo nome ou documento (CPF/CNPJ).
- **Ordenação:** Ordene os fornecedores em ordem crescente ou decrescente.

## Estrutura do Projeto

```bash
start-gov-crud-fornecedores/
├── public/
├── src/
│   ├── assets/
│   ├── components/
│   │   ├── AddProviderModal.vue
│   │   ├── CustomModal.vue
│   │   ├── DataTable.vue
│   │   ├── DeleteProviderModal.vue
│   │   ├── EditProviderModal.vue
│   ├── App.vue
│   ├── main.js
├── .gitignore
├── package.json
├── README.md
└── yarn.lock
```

## Tecnologias Utilizadas

- [Vue.js](https://vuejs.org/)
- [Bootstrap](https://getbootstrap.com/)
- [Yarn](https://yarnpkg.com/)
- [Vite](https://vitejs.dev/)

## Licença

Distribuído sob a licença MIT. Veja `LICENSE` para mais informações.