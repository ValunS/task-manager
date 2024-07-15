<template>
    <form @submit.prevent="register">
        <div class="mb-3">
            <label for="name" class="form-label">Имя:</label>
            <input
                type="text"
                id="name"
                v-model="user.name"
                class="form-control"
                required
            />
            <span v-if="errors.name" class="text-danger">{{
                errors.name[0]
            }}</span>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input
                type="email"
                id="email"
                v-model="user.email"
                class="form-control"
                required
            />
            <span v-if="errors.email" class="text-danger">{{
                errors.email[0]
            }}</span>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль:</label>
            <input
                type="password"
                id="password"
                v-model="user.password"
                class="form-control"
                required
            />
            <span v-if="errors.password" class="text-danger">{{
                errors.password[0]
            }}</span>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label"
                >Подтверждение пароля:</label
            >
            <input
                type="password"
                id="password_confirmation"
                v-model="user.password_confirmation"
                class="form-control"
                required
            />
        </div>
        <button type="submit" class="btn btn-primary">
            Зарегистрироваться
        </button>
    </form>
</template>

<script>
export default {
    data() {
        return {
            user: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            errors: {},
        };
    },
    methods: {
        register() {
            this.errors = {}; // Очищаем ошибки перед отправкой запроса
            this.axios
                .post("/api/register", this.user)
                .then(() => {
                    // Перенаправление на страницу входа после успешной регистрации:
                    this.$store.dispatch("auth/checkToken");
                    this.$router.push({ name: "Login" });
                })
                .catch((error) => {
                    console.error(error);
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                });
        },
    },
};
</script>
