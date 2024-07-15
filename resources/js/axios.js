import axios from "axios";

const instance = axios.create({
    // ваш базовый URL, если нужно
});

// Добавляем перехватчик для добавления токена в заголовки
instance.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    } else {
        delete config.headers.Authorization;
    }
    return config;
});

export default instance;
