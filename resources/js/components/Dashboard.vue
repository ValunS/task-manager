
<template>
  <div class="card">
    <div class="card-header">
      <h2>Панель управления</h2>
    </div>
    <div class="card-body">
      <p>Добро пожаловать, {{ user.name }}!</p>
      <button class="btn btn-primary" @click="logout">Выйти</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: JSON.parse(localStorage.getItem("user")) || {},
    };
  },
  methods: {
    logout() {
      this.axios.post("/api/logout")
        .then(() => {
          localStorage.removeItem("token");
          localStorage.removeItem("user");
          this.$router.push("/auth"); // Перенаправление на страницу авторизации
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
};
</script>

