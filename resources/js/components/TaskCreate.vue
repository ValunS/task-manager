
<template>
  <div class="card">
    <div class="card-header">
      <h2>Создать задачу</h2>
    </div>
    <div class="card-body">
      <form @submit.prevent="createTask">
        <div class="mb-3">
          <label for="title" class="form-label">Название:</label>
          <input type="text" id="title" v-model="task.title" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Описание:</label>
          <textarea id="description" v-model="task.description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
          <label for="status" class="form-label">Статус:</label>
          <select id="status" v-model="task.status" class="form-select">
            <option value="pending">В ожидании</option>
            <option value="in_progress">В процессе</option>
            <option value="completed">Завершено</option>
          </select>
        </div>
        <div class="mb-3">
          <label for="due_date" class="form-label">Дата выполнения:</label>
          <input type="date" id="due_date" v-model="task.due_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      task: {
        title: "",
        description: "",
        status: "pending",
        due_date: "",
      },
    };
  },
  methods: {
    createTask() {
      this.axios.post("/api/tasks", this.task)
        .then(response => {
          // Перенаправляем на страницу созданной задачи 
          this.$router.push({ name: "TaskShow", params: { id: response.data.id } });
        })
        .catch(error => {
          console.error(error);
          // Обработка ошибок, например, вывод сообщений об ошибках валидации
        });
    },
  },
};
</script>

