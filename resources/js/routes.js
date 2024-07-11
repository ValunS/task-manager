
import { createWebHistory, createRouter } from "vue-router";

import Auth from "@/components/Auth/Auth.vue";
import Login from "@/components/Auth/Login.vue";
import Register from "@/components/Auth/Register.vue";
import Dashboard from "@/components/Dashboard.vue"; 
import TaskList from "@/components/TaskList.vue";
import TaskShow from "@/components/TaskShow.vue";
import TaskEdit from "@/components/TaskEdit.vue";
import TaskCreate from "@/components/TaskCreate.vue";

const routes = [
  {
    path: "/auth",
    name: "Auth",
    component: Auth,
    children: [
      {
        path: "", 
        name: "Login",
        component: Login,
      },
      {
        path: "register", 
        name: "Register",
        component: Register,
      },
    ],
  },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: "/tasks",
    name: "Tasks",
    component: TaskList,
    meta: { requiresAuth: true },
  },
  {
    path: "/tasks/:id",
    name: "TaskShow",
    component: TaskShow,
    meta: { requiresAuth: true },
  },
  {
    path: "/tasks/:id/edit",
    name: "TaskEdit",
    component: TaskEdit,
    meta: { requiresAuth: true },
  },
  {
    path: "/tasks/create",
    name: "TaskCreate",
    component: TaskCreate,
    meta: { requiresAuth: true },
  },
  {
    path: "/", // Делаем редирект на /auth, если пользователь не авторизован
    redirect: "/auth",
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Проверка авторизации перед переходом на защищенные маршруты
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (localStorage.getItem("token")) {
      next();
    } else {
      next("/auth"); 
    }
  } else {
    next(); 
  }
});

export default router;

