<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <router-link class="navbar-brand" :to="{ name: 'Tasks' }"
                    >Task Manager</router-link
                >
                <ul v-if="isLoggedIn" class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Tasks' }"
                            >Задачи</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" @click.prevent="logout"
                            >Выйти</a
                        >
                    </li>
                </ul>
                <ul v-else class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Login' }"
                            >Вход</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'Register' }"
                            >Регистрация</router-link
                        >
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container mt-4">
            <router-view />
        </div>
    </div>
</template>

<script>
export default {
    computed: {
        isLoggedIn() {
            return !!localStorage.getItem("token");
        },
    },
    methods: {
        logout() {
            this.axios
                .post("/api/logout")
                .then(() => {
                    localStorage.removeItem("token");
                    localStorage.removeItem("user");
                    this.$router.push("/auth");
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>
