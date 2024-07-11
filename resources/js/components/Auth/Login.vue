
<template>
  <form @submit.prevent="login">
    <div class="mb-3">
      <label for="email" class="form-label">Email:</label>
      <input type="email" id="email" v-model="user.email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Пароль:</label>
      <input type="password" id="password" v-model="user.password" class="form-control" required>
    </div>
    <div class="mb-3">
      <span v-if="errors.other" class="text-danger">{{ errors.other }}</span>
    </div>
    <button type="submit" class="btn btn-primary">Войти</button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      errors: {},
    };
  },
  methods: {
    login() {
      this.axios.post("/api/login", this.user)
        .then(({ data }) => {
          localStorage.setItem("token", data.access_token);
          localStorage.setItem("user", JSON.stringify(data.user));
          // Перенаправление на страницу задач после успешного входа:
          this.$router.push({ name: "Tasks" }); 
        })
        .catch((error) => {
          console.error(error);
          if (error.response && error.response.status === 422) {
            this.errors = error.response.data.errors;
          } else if (error.response && error.response.status === 401) {
            this.errors.other = "Неверный логин или пароль.";
          }
        });
    },
  },
};
</script>

