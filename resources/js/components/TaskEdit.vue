<template>
    <div v-if="task" class="card">
        <div class="card-header">
            <h2>Редактировать задачу</h2>
        </div>
        <div class="card-body">
            <form @submit.prevent="updateTask">
                <div class="mb-3">
                    <label for="title" class="form-label">Название:</label>
                    <input
                        type="text"
                        id="title"
                        v-model="task.title"
                        class="form-control"
                        required
                    />
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label"
                        >Описание:</label
                    >
                    <textarea
                        id="description"
                        v-model="task.description"
                        class="form-control"
                    ></textarea>
                </div>
                <div class="mb-3">
                    <label for="status" class="form-label">Статус:</label>
                    <select
                        id="status"
                        v-model="task.status"
                        class="form-select"
                    >
                        <option value="pending">В ожидании</option>
                        <option value="in_progress">В процессе</option>
                        <option value="completed">Завершено</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="due_date" class="form-label"
                        >Дата выполнения:</label
                    >
                    <input
                        type="date"
                        id="due_date"
                        v-model="task.due_date"
                        class="form-control"
                        required
                    />
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            task: null,
        };
    },
    mounted() {
        this.fetchTask();
    },
    methods: {
        fetchTask() {
            this.axios
                .get(`/api/tasks/${this.$route.params.id}`)
                .then((response) => {
                    this.task = response.data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        updateTask() {
            this.axios
                .put(`/api/tasks/${this.task.id}`, this.task)
                .then(() => {
                    this.$router.push({
                        name: "TaskShow",
                        params: { id: this.task.id },
                    });
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
};
</script>
