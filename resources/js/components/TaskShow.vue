<template>
    <div v-if="task" class="card">
        <div class="card-header">
            <h2>{{ task.title }}</h2>
        </div>
        <div class="card-body">
            <p>{{ task.description }}</p>
            <p><strong>Статус:</strong> {{ translateStatus(task.status) }}</p>
            <p><strong>Дата выполнения:</strong> {{ task.due_date }}</p>
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
