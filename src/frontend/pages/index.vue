<template>
  <section class="section">
    <h1 class="mb-20">
      All transactinsas (name, quantity, price, direction, date, action)
    </h1>
    <div v-if="isLoading">Loading...</div>

    <div class="list">
      <div
        v-for="transaction in transactions"
        :key="transaction.id"
        class="list-item"
      >
        <div>
          <b>{{ transaction.product }}</b>
        </div>
        <div>{{ transaction.quantity }}</div>
        <div>{{ transaction.price + " €" }}</div>
        <div>{{ transaction.direction }}</div>
        <div>{{ transaction.date.split("T")[0] }}</div>

        <b-button type="is-danger" @click="deleteTransaction(transaction.id)"
          >Delete</b-button
        >
        <p class="notification" v-if="message">{{ message }}</p>
      </div>
    </div>
  </section>
</template>

<script>
import api from "../mixins.js/api.js";

export default {
  name: "IndexPage",
  async fetch() {
    this.getAll();
  },
  mixins: [api],
  data() {
    return {
      transactions: [],
      isLoading: false,
      message: "",
    };
  },
  methods: {
    async getAll() {
      this.isLoading = true;

      try {
        const response = await this.getAllTransactions();

        console.log(response.data);
        this.transactions = response.data;
      } catch (error) {
        console.error(error);
      } finally {
        this.isLoading = false;
      }
    },
    async deleteTransaction(id) {
      try {
        this.message = "Deleteing transaction";
        const response = await this.deleteSingleTransaction(id);

        this.message = response.message;

        this.transactions = this.transactions.filter(
          (transaction) => transaction.id !== id
        );
      } catch (error) {
        this.message = error;
      }
      setTimeout(() => {
        this.message = "";
      }, 3000);
    },
  },
  created() {
    if (this.transactions.length === 0) {
      this.getAll();
    }
  },
};
</script>

<style scoped>
.list-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 50px;
  padding: 20px;
  margin-bottom: 16px;
  border-bottom: 1px solid rgb(107, 107, 107);
}
.list-item > div {
  flex: 1;
}
.notification {
  position: fixed;
  top: 100px;
  bottom: 0;
  right: 0;
  left: 0;

  width: 300px;
  height: max-content;
  margin: 0 auto;
  border: 1px solid rgb(107, 107, 107);
}
</style>
