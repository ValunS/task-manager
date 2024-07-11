<template>
    <div>
        <h2 class="mb-3">Задачи</h2>

        <div class="mb-3">
            <router-link :to="{ name: 'TaskCreate' }" class="btn btn-primary">
                Создать задачу
            </router-link>
        </div>

        <h2 class="mb-3">Фильтры</h2>

        <div class="mb-3">
            <input
                type="date"
                v-model="filters.due_date"
                class="form-control"
                placeholder="Дата выполнения"
            />
        </div>

        <div class="mb-3">
            <select v-model="filters.status" class="form-select">
                <option value="">Все статусы</option>
                <option value="pending">В ожидании</option>
                <option value="in_progress">В процессе</option>
                <option value="completed">Завершено</option>
            </select>
        </div>

        <button @click="fetchTasks" class="btn btn-secondary mb-3">
            Применить фильтры
        </button>

        <div v-if="loading">Загрузка...</div>
        <div v-else-if="!tasks.length" class="alert alert-info">
            Список задач пуст.
        </div>
        <div v-else>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Статус</th>
                        <th>Дата выполнения</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="task in tasks" :key="task.id">
                        <td>
                            {{ task.id }}
                        </td>
                        <td>
                            <router-link
                                :to="{
                                    name: 'TaskShow',
                                    params: { id: task.id },
                                }"
                            >
                                {{ task.title }}
                            </router-link>
                        </td>
                        <td>{{ translateStatus(task.status) }}</td>
                        <td>{{ task.due_date }}</td>
                        <td>
                            <router-link
                                :to="{
                                    name: 'TaskEdit',
                                    params: { id: task.id },
                                }"
                                class="btn btn-sm btn-secondary me-3"
                            >
                                Редактировать
                            </router-link>
                            <button
                                @click="deleteTask(task.id)"
                                class="btn btn-sm btn-danger"
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tasks: [],
            loading: true,
            filters: {
                due_date: "",
                status: "",
            },
        };
    },
    mounted() {
        this.fetchTasks();
    },
    methods: {
        fetchTasks() {
            this.loading = true;
            this.axios
                .get("/api/tasks", { params: this.filters })
                .then((response) => {
                    this.tasks = response.data;
                })
                .catch((error) => {
                    console.error(error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        deleteTask(taskId) {
            if (confirm("Вы уверены, что хотите удалить задачу?")) {
                this.axios
                    .delete(`/api/tasks/${taskId}`)
                    .then(() => {
                        this.tasks = this.tasks.filter(
                            (task) => task.id !== taskId
                        );
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        },
        translateStatus(status) {
            switch (status) {
                case "pending":
                    return "В ожидании";
                case "in_progress":
                    return "В процессе";
                case "completed":
                    return "Завершено";
                default:
                    return status; // На случай, если статус не опознан
            }
        },
    },
};
</script>
