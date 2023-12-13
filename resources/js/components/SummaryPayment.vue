<template>
  <div class="container mt-4">
    <p v-if="errors.length">
      <b>Favor, verifique os errors a seguir:</b>
      <ul>
        <li :key="index" v-for="(error,index) in errors">{{ error }}</li>
      </ul>
    </p>

    <form @submit.prevent="send">
      <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input
          id="name"
          label="name"
          v-model="form.name"
          class="form-control"
          placeholder="Nome"
        />
      </div>
      
      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input
          id="email"
          label="email"
          v-model="form.email"
          class="form-control"
          placeholder="E-mail"
        />
      </div>
      
      <div class="mb-3">
        <label for="cpfCnpj" class="form-label">CPF</label>
        <input
          id="cpfCnpj"
          label="cpfCnpj"
          v-model="form.cpf"
          class="form-control"
          placeholder="CPF/CNPJ"
          maxlength="14"
          @input="cpfMask"
        />
      </div>
      
      <div class="mb-3">
        <label for="cpfCnpj" class="form-label">CNPJ</label>
        <input
          id="cpfCnpj"
          label="cpfCnpj"
          v-model="form.cnpj"
          class="form-control"
          placeholder="CPF/CNPJ"
          maxlength="18"
          @input="cnpjMask"
        />
      </div>

      <div class="mb-3">
        <label for="billingType" class="form-label">Tipo de Pagamento</label>
        <select
          id="billingType"
          label="billingType"
          v-model="form.billingType"
          class="form-control"
          placeholder="Tipo de Pagamento"
          @input="changeBilling"
        >
          <option value="">--SELECIONE--</option>
          <option value="CREDIT_CARD">Cartão de Crédito</option>
          <option value="PIX">PIX</option>
          <option value="BOLETO">Boleto</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="value" class="form-label">Valor</label>
        <input
          id="value"
          label="value"
          v-model="form.value"
          class="form-control"
          placeholder="Valor"
        />
      </div>

      <div class="mb-3">
          <label for="dueDate" class="form-label">Vencimento</label>
          <input
            type="date"
            id="dueDate"
            label="dueDate"
            v-model="form.dueDate"
            class="form-control"
            placeholder="Vencimento"
          />
        </div>

      <div 
        v-if="form.billingType === 'CREDIT_CARD'"
        ref="cartao"
      >
        <div class="mb-3">
          <label for="number" class="form-label">Número do Cartão</label>
          <input
            id="number"
            label="number"
            v-model="form.creditCard.number"
            class="form-control"
            placeholder="number"
          />
        </div>

        <div class="mb-3">
          <label for="expiryMonth" class="form-label">Mês vencimento</label>
          <input
            id="expiryMonth"
            label="expiryMonth"
            v-model="form.creditCard.expiryMonth"
            class="form-control"
            placeholder="Vencimento"
            @input="onlyDigits"
          />
        </div>
        
        <div class="mb-3">
          <label for="expiryYear" class="form-label">Ano vencimento</label>
          <input
            id="expiryYear"
            label="expiryYear"
            v-model="form.creditCard.expiryYear"
            class="form-control"
            placeholder="Ano vencimento"
            @input="onlyDigits"
          />
        </div>
        
        <div class="mb-3">
          <label for="ccv" class="form-label">CCV</label>
          <input
            id="ccv"
            label="ccv"
            v-model="form.creditCard.ccv"
            class="form-control"
            placeholder="CCV"
          />
        </div>
        
        <div class="mb-3">
          <label for="postalCode" class="form-label">CEP titular</label>
          <input
            id="postalCode"
            label="postalCode"
            v-model="form.creditCardHolderInfo.postalCode"
            class="form-control"
            placeholder="CEP titular"
          />
        </div>
        
        <div class="mb-3">
          <label for="addressNumber" class="form-label">Número endereço titular</label>
          <input
            id="addressNumber"
            label="addressNumber"
            v-model="form.creditCardHolderInfo.addressNumber"
            class="form-control"
            placeholder="Número endereço titular"
          />
        </div>
        
        <div class="mb-3">
          <label for="phone" class="form-label">Telefone titular</label>
          <input
            id="phone"
            label="phone"
            v-model="form.creditCardHolderInfo.phone"
            class="form-control"
            placeholder="Telefone titular"
          />
        </div>
      </div>

      <div class="mb-3">
        <button
          type="button"
          @click="send"
          class="btn btn-primary"
        >Enviar</button>
        
        <a
          v-show="isBillet()"
          target="_blank"
          ref="link-boleto"
          class="btn btn-success"
        >Pagar</a>

        <a
          v-show="isPix()"
          ref="btn-qrocde"
          data-bs-toggle="modal" 
          data-bs-target="#paymentModal"
          class="btn btn-success"
        >Ver QRCODE</a>

        <a
          v-show="isCreditCard()"
          target="_blank"
          ref="link-cartao"
          class="btn btn-success"
        >Link Cartão de Crédito</a>
      </div>
    </form>

    <div class="modal fade" id="paymentModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Pagar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <textarea ref="qrcode" name="" id="" cols="30" rows="10"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" @click="copyText" class="btn btn-primary">Copia</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

  </div>  
</template>

<script>

import axios from 'axios';

export default {
  name: 'SummaryPayment',
  data: () => ({
    form: {
      name: '',
      email: '',
      cpf: '',
      cnpj: '',
      billingType: '',
      value: 0,
      dueDate: '',
      creditCard: {},
      creditCardHolderInfo: {}
    },
    billingType: null,
    errors: []
  }),
  created() {
    this.loadData();
  },
  methods: {
    async loadData() {
      
      // let data = await axios.get('https://www.google.com');
    },
    validateForm() {
      this.errors = [];
      if (!this.form.name) {
        this.errors.push('Nome obrigatório');
      }
      if (!this.form.email) {
        this.errors.push('E-mail obrigatório');
      }
      if (!this.form.cpf && !this.form.cnpj) {
        this.errors.push('CPF ou CNPJ obrigatório');
      }
      if (!this.form.billingType) {
        this.errors.push('Tipo de pagamento obrigatório');
      }
      if (!this.form.value) {
        this.errors.push('Valor obrigatório');
      }
      if (!this.form.dueDate) {
        this.errors.push('Data de vencimento obrigatória');
      }

      if (this.errors.length) {
        return false;
      }

      return true;

    },
    async send() {

      if (!this.validateForm()) {
        return false;
      }

      try {

        const customerResponse = await this.createCustomer({
          name: this.form.name,
          email: this.form.email,
          cpfCnpj: this.form.cpf ?? this.form.cnpj
        });
        
        if (customerResponse.status === 200) {
  
          let data = {
            billingType: this.form.billingType,
            value: this.form.value,
            dueDate: this.form.dueDate,
            customer: customerResponse.data.data.provider_id
          };
  
          data = {...data, ...this.form};
          data.creditCard.holderName = this.form.name;
          data.creditCardHolderInfo.name = this.form.name;
          data.creditCardHolderInfo.email = this.form.email;
          data.creditCardHolderInfo.cpfCnpj = (this.form.cpf ?? this.form.cnpj);
  
          const self = this;
          const paymentResponse = await this.createPayment(data);
  
          if (paymentResponse.status === 200) {
            this.billingType = paymentResponse.data.data.billingType;
            
            if (this.isBillet()) {
              this.$refs['link-boleto'].href = paymentResponse.data.data.invoiceUrl;
            } else if (this.isPix()) {
              this.$refs['qrcode'].append(paymentResponse.data.data.qrCode);
            } else if (this.isCreditCard()) {
              this.$refs['link-cartao'].href = paymentResponse.data.data.invoiceUrl;
            }
            
          }        
        }
      } catch (error) {
        const errors = error.response.data.errors;
        let keys = Object.keys(errors);

        const self = this;
        keys.map(function(index) {
          errors[index].map(function(message) {
            self.errors.push(message);
          })
        });
      }


    },
    copyText() {
      const element = this.$refs['qrcode'];
      element.select();
      document.execCommand("copy");
    },
    isBillet() {
      return (this.billingType === 'BOLETO');
    },
    isPix() {
      return (this.billingType === 'PIX');
    },
    isCreditCard() {
      return (this.billingType === 'CREDIT_CARD');
    },
    changeBilling() {
      if (this.form.billingType === 'CREDIT_CARD') {
        this.form.creditCardHolderInfo.name = this.form.creditCard.holdernName;
        this.form.creditCardHolderInfo.cpfCnpj = (this.form.cpf ?? this.form.cnpj);
        this.form.creditCardHolderInfo.email = this.form.email;
      }
    },
    async createCustomer(data = {}) {
      const url = 'http://127.0.0.1:8000/api/customers';

      return await axios.post(url, data);
    },
    async createPayment(data = {}) {
      const url = 'http://127.0.0.1:8000/api/payments';

      return await axios.post(url, data);
    },
    cpfMask() {
      this.form.cpf = this.form.cpf.replace(/\D/g, '');
      this.form.cpf = this.form.cpf.replace(/(\d{3})(\d)/, '$1.$2');
      this.form.cpf = this.form.cpf.replace(/(\d{3})(\d)/, '$1.$2');
      this.form.cpf = this.form.cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    },
    cnpjMask() {
      this.form.cnpj = this.form.cnpj.replace(/\D/g, ''); // Remove non-numeric characters
      this.form.cnpj = this.form.cnpj.replace(/(\d{2})(\d)/, '$1.$2');
      this.form.cnpj = this.form.cnpj.replace(/(\d{3})(\d)/, '$1.$2');
      this.form.cnpj = this.form.cnpj.replace(/(\d{3})(\d)/, '$1/$2');
      this.form.cnpj = this.form.cnpj.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
    },
    dueDateMask() {
      this.form.dueDate = this.form.dueDate.replace(/\D/g, '');
    },
    onlyDigits() {
      this.form.creditCard.expiryMonth = this.form.creditCard.expiryMonth.replace(/\D/g, '');
    }
  }
}
</script>
