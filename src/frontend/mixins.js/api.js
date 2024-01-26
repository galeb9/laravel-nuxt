export default {
  data() {
    return {
      apiUrl: "http://localhost:80/api",
    };
  },
  methods: {
    getAllTransactions() {
      return this.$axios.$get(this.apiUrl + "/transactions");
    },
    deleteSingleTransaction(id) {
      return this.$axios.$delete(`${this.apiUrl}/transactions/${id}`);
    },
    createSingleTransaction(payload) {
      return this.$http.post(this.apiUrl + "/transactions", payload);
    },
  },
};
