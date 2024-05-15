<template>
  <v-card class="pa-5">
    <v-card-title>
      <h3>Consulta de Cadastro</h3>
      <div class="align-right">
        <v-btn class="button-top" color="success" @click="openNewCadastroDialog">
          <v-icon left>mdi-plus</v-icon>
          Novo Cadastro
        </v-btn>
      </div>
    </v-card-title>
    <v-card-text>
      <v-form>
        <v-text-field v-model="searchTerm" label="Digite o Identificador, Nome ou CPF" type="text"></v-text-field>
        <v-btn color="primary" @click="onSubmit">Consultar</v-btn>
      </v-form>
      <v-divider class="my-5"></v-divider>
      <v-list v-if="searchResults.length && searchPerformed">
        <v-list-item v-for="(result, index) in pagedResults" :key="index">
          <v-list-item-content>
            <v-list-item-title>{{ result.name }}</v-list-item-title>
            <v-list-item-subtitle>ID: {{ result.id }} - CPF: {{ result.cpf }}</v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-action class="align-right">
            <v-btn class="button-botton" color="blue" @click="visualizar(result)">
              <v-icon>mdi-eye</v-icon>
              Visualizar
            </v-btn>
            <v-btn class="button-botton" color="yellow" @click="editEndereco(result)">
              <v-icon>mdi-pencil</v-icon>
              Editar Endereço
            </v-btn>

          </v-list-item-action>
        </v-list-item>
      </v-list>
      <v-pagination v-if="searchResults.length > itemsPerPage" v-model="currentPage"
        :length="Math.ceil(searchResults.length / itemsPerPage)"></v-pagination>
      <p v-else-if="!searchResults.length">Nenhum resultado encontrado.</p>
    </v-card-text>
    <v-dialog v-model="exibirDadosDialog">
      <v-card>
        <v-card-title>
          Dados do Usuário
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field label="Nome" v-model="exibirDados.name" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Nome Social" v-model="exibirDados.social_name" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="CPF" v-model="exibirDados.cpf" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Nome do Pai" v-model="exibirDados.father_name" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Nome da Mãe" v-model="exibirDados.mother_name" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Telefone" v-model="exibirDados.phone" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Email" v-model="exibirDados.email" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Tipo de Endereço" v-model="exibirDados.tipo" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="CEP" v-model="exibirDados.cep" disabled></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field label="Logradouro" v-model="exibirDados.logradouro" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Número" v-model="exibirDados.numero" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Complemento" v-model="exibirDados.complemento" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Bairro" v-model="exibirDados.bairro" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Estado" v-model="exibirDados.estado" disabled></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field label="Cidade" v-model="exibirDados.cidade" disabled></v-text-field>
              </v-col>

            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" @click="exibirDadosDialog = false">Fechar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="newCadastroDialog" max-width="600">
      <v-card>
        <v-card-title>
          <span class="headline">Novo Cadastro</span>
        </v-card-title>
        <v-card-text>
          <div v-if="errorMessages.length" class="alert alert-danger">
            <ul>
              <li v-for="message in errorMessages" :key="message">{{ message }}</li>
            </ul>
          </div>

          <v-form @submit.prevent="submitNewCadastro">
            <v-alert v-if="errorMessages && errorMessages.name" class="error-alert" type="error">{{
              errorMessages.name[0] }}</v-alert>
            <v-text-field v-model="newCadastro.name" label="Nome" required></v-text-field>
            <v-alert v-if="errorMessages && errorMessages.social_name" class="error-alert" type="error">{{
              errorMessages.social_name[0] }}</v-alert>
            <v-text-field v-model="newCadastro.social_name" label="Nome Social"></v-text-field>
            <v-alert v-if="errorMessages && errorMessages.cpf" class="error-alert" type="error">{{ errorMessages.cpf[0]
              }}</v-alert>
            <v-text-field v-model="newCadastro.cpf" label="CPF" required v-mask="'###.###.###-##'"></v-text-field>
            <v-alert v-if="errorMessages && errorMessages.father_name" class="error-alert" type="error">{{
              errorMessages.father_name[0] }}</v-alert>
            <v-text-field v-model="newCadastro.father_name" label="Nome do Pai"></v-text-field>
            <v-alert v-if="errorMessages && errorMessages.mother_name" class="error-alert" type="error">{{
              errorMessages.mother_name[0] }}</v-alert>
            <v-text-field v-model="newCadastro.mother_name" label="Nome da Mãe"></v-text-field>
            <v-alert v-if="errorMessages && errorMessages.phone" class="error-alert" type="error">{{
              errorMessages.phone[0] }}</v-alert>
            <v-text-field v-model="newCadastro.phone" label="Telefone" v-mask="'(##) #####-####'"></v-text-field>
            <v-alert v-if="errorMessages && errorMessages.email" class="error-alert" type="error">{{
              errorMessages.email[0] }}</v-alert>
            <v-text-field v-model="newCadastro.email" label="Email" required></v-text-field>
            <v-btn color="primary" type="submit">Criar Cadastro</v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="editarEnderecoDialog">
      <v-card>
        <v-card-title>
          Editar Endereço
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.tipo" class="error-alert" type="error">{{
                  errorMessages.tipo[0] }}</v-alert>
                <v-select v-model="enderecoEditado.tipo" label="Tipo de Endereço"
                  :items="['Residencial', 'Comercial']"></v-select>
              </v-col>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.cep" class="error-alert" type="error">{{
                  errorMessages.cep[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.cep" label="CEP"
                  @input="buscarInformacoesCEP(enderecoEditado.cep)"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-alert v-if="errorMessages && errorMessages.logradouro" class="error-alert" type="error">{{
                  errorMessages.logradouro[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.logradouro" label="Logradouro"
                  :disabled="!enderecoEditado.camposHabilitados"></v-text-field> </v-col>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.numero" class="error-alert" type="error">{{
                  errorMessages.numero[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.numero" label="Número"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.complemento" class="error-alert" type="error">{{
                  errorMessages.complemento[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.complemento" label="Complemento"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.bairro" class="error-alert" type="error">{{
                  errorMessages.bairro[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.bairro" label="Bairro"
                  :disabled="!enderecoEditado.camposHabilitados"></v-text-field> </v-col>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.estado" class="error-alert" type="error">{{
                  errorMessages.estado[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.estado" label="Estado"
                  :disabled="!enderecoEditado.camposHabilitados"></v-text-field> </v-col>
              <v-col cols="12" sm="6">
                <v-alert v-if="errorMessages && errorMessages.cidade" class="error-alert" type="error">{{
                  errorMessages.cidade[0] }}</v-alert>
                <v-text-field v-model="enderecoEditado.cidade" label="Cidade"
                  :disabled="!enderecoEditado.camposHabilitados"></v-text-field> </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-btn color="blue darken-1" @click="salvarEnderecoEditado">Salvar</v-btn>
          <v-btn @click="editarEnderecoDialog = false">Cancelar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      searchTerm: '',
      searchResults: [],
      currentPage: 1,
      itemsPerPage: 10,
      newCadastroDialog: false,
      editarEnderecoDialog: false,
      exibirDadosDialog: false,
      newCadastro: {
        name: '',
        social_name: '',
        cpf: '',
        father_name: '',
        mother_name: '',
        phone: '',
        email: ''
      },
      enderecoEditado: {
        tipo: '',
        cep: '',
        logradouro: '',
        numero: '',
        complemento: '',
        bairro: '',
        estado: '',
        cidade: '',
        camposHabilitados: true
      },
      exibirDados: {
        name: '',
        social_name: '',
        cpf: '',
        father_name: '',
        mother_name: '',
        phone: '',
        email: '',
        tipo: '',
        cep: '',
        logradouro: '',
        numero: '',
        complemento: '',
        bairro: '',
        estado: '',
        cidade: '',
      },
      errorMessages: [],
      searchPerformed: false
    };
  },
  computed: {
    pagedResults() {
      const startIndex = (this.currentPage - 1) * this.itemsPerPage;
      const endIndex = startIndex + this.itemsPerPage;
      return this.searchResults.slice(startIndex, endIndex);
    }
  },
  methods: {
    async onSubmit() {
      if (this.searchTerm.trim() !== '') {
        try {
          const response = await axios.get(`http://127.0.0.1:8000/api/users?search=${this.searchTerm}`);
          this.searchResults = response.data;
          this.currentPage = 1;
          this.searchPerformed = true; // Define searchPerformed como verdadeiro após a busca ser realizada
        } catch (error) {
          console.error('Erro ao buscar cadastros:', error);
          this.searchResults = [];
        }
      }
    },
    async salvarEnderecoEditado() {
      try {
        const response = await axios.post(`http://127.0.0.1:8000/api/enderecos/`, this.enderecoEditado);
        console.log('Endereço editado:', response.data);
        this.editarEnderecoDialog = false;
        // Limpar os dados do endereço editado após salvar
        this.enderecoEditado = {
          user_id: this.enderecoEditado.id,
          tipo: '',
          cep: '',
          logradouro: '',
          numero: '',
          complemento: '',
          bairro: '',
          estado: '',
          cidade: '',
          camposHabilitados: true
        };
        window.location.reload();
      } catch (error) {
        if (error.response.status === 422) {
          this.errorMessages = error.response.data.errors;
        } else {
          console.error('Erro ao criar novo cadastro:', error);
        }
      }
    },

    async buscarInformacoesCEP(cep) {
      try {
        const response = await axios.get(`https://viacep.com.br/ws/${cep}/json/`);
        if (response.data.erro) {
          console.error('CEP não encontrado');
        } else {
          this.enderecoEditado.logradouro = response.data.logradouro;
          this.enderecoEditado.complemento = response.data.complemento;
          this.enderecoEditado.bairro = response.data.bairro;
          this.enderecoEditado.cidade = response.data.localidade;
          this.enderecoEditado.estado = response.data.uf;

          // Desabilita os campos de endereço após obter uma resposta válida do CEP
          this.desabilitarCamposEndereco();
        }
      } catch (error) {
        console.error('Erro ao buscar informações do CEP:', error);
      }
    },
    desabilitarCamposEndereco() {
      // Define a variável de estado ou propriedade de cada campo de endereço como desabilitada
      this.enderecoEditado.camposHabilitados = false;
    },
    openNewCadastroDialog() {
      this.newCadastroDialog = true;
    },
    async submitNewCadastro() {
      try {
        const response = await axios.post('http://127.0.0.1:8000/api/users', this.newCadastro);
        console.log('Novo cadastro:', response.data);
        this.newCadastroDialog = false;
        this.clearNewCadastro();
      } catch (error) {
        if (error.response.status === 422) {
          this.errorMessages = error.response.data.errors;
        } else {
          console.error('Erro ao criar novo cadastro:', error);
        }
      }
    },
    clearNewCadastro() {
      this.newCadastro = {
        name: '',
        social_name: '',
        cpf: '',
        father_name: '',
        mother_name: '',
        phone: '',
        email: ''
      };
    },
    editEndereco(result) {
      if (result.street) {
        this.enderecoEditado = {
          tipo: result.street.tipo,
          cep: result.street.cep,
          logradouro: result.street.logradouro,
          numero: result.street.numero,
          complemento: result.street.complemento,
          bairro: result.street.bairro,
          estado: result.street.estado,
          cidade: result.street.cidade,
          camposHabilitados: false
        };

      }
      this.editarEnderecoDialog = true;

    },

    visualizar(result) {

      console.log(result);
      this.exibirDados = {
        name: result.name,
        social_name: result.social_name,
        cpf: result.cpf,
        father_name: result.father_name,
        mother_name: result.mother_name,
        phone: result.phone,
        email: result.email,
        tipo: result.street.tipo,
        cep: result.street.cep,
        logradouro: result.street.logradouro,
        numero: result.street.numero,
        complemento: result.street.complemento,
        bairro: result.street.bairro,
        estado: result.street.estado,
        cidade: result.street.cidade,
      };



      this.exibirDadosDialog = true;

    },

  },

};
</script>

<style scoped>
.pa-5 {
  padding: 20px;
}

.align-right {
  float: right;
}

.button-top {
  margin-top: -60px;
}

.button-botton {
  margin-top: -30px;
}

.error-alert {
  font-size: 12px;
  padding: 5px 10px;
  border-radius: 4px;
}
</style>
