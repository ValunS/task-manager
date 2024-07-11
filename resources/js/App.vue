<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <router-link class="navbar-brand" :to="{ name: 'Dashboard' }"
                    >Task Manager</router-link
                >
                <ul v-if="isLoggedIn" class="navbar-nav">
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
