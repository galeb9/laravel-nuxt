<template>
  <div class="add-transaction section" style="max-width: 500px">
    <h2>Add Transaction</h2>
    <p>{{ apiUrl }}</p>
    <br />
    <form @submit.prevent="submitTransaction">
      <div class="block">
        <label>Product:</label>
        <input type="text" v-model="transaction.product" />
        <span v-if="validationErrors.product">{{
          validationErrors.product
        }}</span>
      </div>
      <div class="block">
        <label>Quantity:</label>
        <input type="number" v-model="transaction.quantity" />
        <span v-if="validationErrors.quantity">quantity is required</span>
      </div>
      <div class="block">
        <label>Price:</label>
        <input type="number" step="0.01" v-model="transaction.price" />
        <span v-if="validationErrors.price">price is required</span>
      </div>
      <div class="block">
        <label>
          <input
            v-model="transaction.direction"
            type="radio"
            name="direction"
            value="buy"
          />
          Buy
        </label>
        <label>
          <input
            v-model="transaction.direction"
            type="radio"
            name="direction"
            value="sell"
          />
          Sell
        </label>
        <span v-if="validationErrors.direction">Direction is required</span>
      </div>

      <button type="submit" :disabled="message.length > 0">
        Add Transaction
      </button>
      <p v-if="message">{{ message }}</p>
    </form>
  </div>
</template>

<script>
import api from "../mixins.js/api.js";

export default {
  name: "AddTransaction",
  mixins: [api],
  data() {
    return {
      transaction: {
        date: null,
        product: "",
        quantity: 0,
        price: 0,
        direction: "buy",
      },
      validationErrors: {},
      message: "",
    };
  },
  methods: {
    async submitTransaction() {
      this.transaction.date = this.formatDate();
      console.log(this.transaction);

      if (this.validateForm()) {
        this.addTransaction(this.transaction);
      }
    },

    async addTransaction(transaction) {
      this.message = "Proccesing";
      try {
        const response = await this.createSingleTransaction(transaction);

        this.resetForm();
        this.message =
          "succesfully added a transaction, rederecting to all transactions";

        setTimeout(() => {
          this.$router.push("/");
        }, 3000);
      } catch (error) {
        console.error(error); // Handle error
      } finally {
        //   when resolved go to home
      }
    },
    validateForm() {
      this.validationErrors = {};

      if (!this.transaction.product.trim()) {
        this.validationErrors.product = "Product is required";
      }

      if (this.transaction.quantity <= 0) {
        this.validationErrors.quantity = "Quantity must be greater than 0";
      }

      if (this.transaction.price <= 0) {
        this.validationErrors.price = "Price must be greater than 0";
      }

      if (!this.transaction.direction.trim()) {
        this.validationErrors.direction = "Direction is required";
      }

      return Object.keys(this.validationErrors).length === 0;
    },
    formatDate() {
      const currentDate = new Date();
      return currentDate.toISOString().slice(0, 19).replace("T", " ");
    },
    resetForm() {
      this.transaction = {
        date: null,
        product: "",
        quantity: 0,
        price: 0,
        direction: "buy",
      };

      // Clear validation errors
      this.validationErrors = {};
    },
  },
};
</script>

<style></style>
