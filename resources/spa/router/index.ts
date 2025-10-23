import { createRouter, createWebHistory, RouteRecordRaw } from "vue-router";

const routes: RouteRecordRaw[] = [
  {
    path: '/',
    name: 'Dashboard',
    component: () => import('@/pages/auth/DashboardPage.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/pages/guest/LoginPage.vue')
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('@/pages/error/NotFoundPage.vue'),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router;
