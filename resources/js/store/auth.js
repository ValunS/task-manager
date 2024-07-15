import axios from "@/axios";

const auth = {
    namespaced: true,
    state: () => ({
        user: JSON.parse(localStorage.getItem("user")) || null,
        token: localStorage.getItem("token") || null,
        isTokenValid: null,
        loginError: null,
        registerError: null,
    }),
    mutations: {
        setUser(state, user) {
            state.user = user;
            localStorage.setItem("user", JSON.stringify(user));
        },
        setToken(state, token) {
            state.token = token;
            localStorage.setItem("token", token);
        },
        setIsTokenValid(state, isValid) {
            state.isTokenValid = isValid;
        },
        setLoginError(state, error) {
            state.loginError = error;
        },
        setRegisterError(state, error) {
            state.registerError = error;
        },
        clearErrors(state) {
            state.loginError = null;
            state.registerError = null;
        },
    },
    getters: {
        isLoggedIn: (state) => !!state.token && state.isTokenValid,
        user: (state) => state.user,
        loginError: (state) => state.loginError,
        registerError: (state) => state.registerError,
        isTokenValid: (state) => state.isTokenValid,
    },
    actions: {
        async checkToken({ commit, state }) {
            commit("clearErrors");
            if (!state.token) {
                commit("setIsTokenValid", false);
                return;
            }
            try {
                const response = await axios.get("/api/user");
                if (response.status === 200) {
                    commit("setUser", response.data);
                    commit("setIsTokenValid", true);
                } else {
                    console.error("Неожиданный ответ сервера:", response);
                }
            } catch (error) {
                commit("setIsTokenValid", false);
                commit("setToken", null);
                localStorage.removeItem("token");
                localStorage.removeItem("user");
                console.error("Ошибка проверки токена:", error);
            }
        },
        async login({ commit }, credentials) {
            commit("clearErrors");
            try {
                const response = await axios.post("/api/login", credentials);
                commit("setToken", response.data.access_token);
                commit("setUser", response.data.user);
                commit("setIsTokenValid", true);
                return true;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    commit("setLoginError", error.response.data.errors);
                } else {
                    commit(
                        "setLoginError",
                        "Произошла ошибка. Пожалуйста, попробуйте позже."
                    );
                    console.error("Ошибка входа:", error);
                }
                throw error;
            }
        },
        async register({ commit }, userData) {
            commit("clearErrors");
            try {
                const response = await axios.post("/api/register", userData);
                return true;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    commit("setRegisterError", error.response.data.errors);
                } else {
                    commit(
                        "setRegisterError",
                        "Произошла ошибка. Пожалуйста, попробуйте позже."
                    );
                    console.error("Ошибка регистрации:", error);
                }
                throw error;
            }
        },
        logout({ commit }) {
            commit("setToken", null);
            commit("setUser", null);
            commit("setIsTokenValid", false);
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            axios.post("/api/logout").catch((error) => {
                console.error("Ошибка при выходе:", error);
            });
        },
    },
};

export default auth;
