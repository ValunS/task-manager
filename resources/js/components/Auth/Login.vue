<template>
    <form @submit.prevent="handleLogin">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input
                type="email"
                id="email"
                v-model="credentials.email"
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
                v-model="credentials.password"
                class="form-control"
                required
            />
            <span v-if="errors.password" class="text-danger">{{
                errors.password[0]
            }}</span>
        </div>
        <div v-if="otherError" class="alert alert-danger">
            {{ otherError }}
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
    data() {
        return {
            credentials: {
                email: "",
                password: "",
            },
        };
    },
    computed: {
        ...mapGetters("auth", ["loginError"]),
        ...mapGetters("auth", ["isLoggedIn"]),
        errors() {
            return this.loginError || {};
        },
        otherError() {
            if (typeof this.loginError === "string") {
                return this.loginError;
            } else if (this.loginError && this.loginError.other) {
                return this.loginError.other[0];
            }
            return null;
        },
    },
    methods: {
        ...mapActions("auth", ["login"]),
        async handleLogin() {
            try {
                await this.login(this.credentials);
                console.log(this.isLoggedIn);
                this.$router.push({ name: "Dashboard" });
            } catch (error) {
                console.error("Ошибка при попытке входа:", error);
            }
        },
    },
};
</script>
