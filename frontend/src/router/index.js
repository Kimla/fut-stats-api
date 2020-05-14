import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routes = [
    {
        path: '/',
        name: 'Home',
        meta: { requiresAuth: true },
        component: () => import(/* webpackChunkName: "home" */ '../views/Home.vue')
    },
    {
        path: '/wl',
        name: 'WeekendLeagues',
        meta: { requiresAuth: true },
        component: () => import(/* webpackChunkName: "WeekendLeagues" */ '../views/WeekendLeagues.vue')
    },
    {
        path: '/wl/:id',
        name: 'WeekendLeague',
        meta: { requiresAuth: true },
        component: () => import(/* webpackChunkName: "WeekendLeague" */ '../views/WeekendLeague.vue')
    },
    {
        path: '/watch',
        name: 'PlayerWatch',
        meta: { requiresAuth: true },
        component: () => import(/* webpackChunkName: "PlayerWatch" */ '../views/PlayerWatch.vue')
    },
    {
        path: '/team',
        name: 'Team',
        meta: { requiresAuth: true },
        component: () => import(/* webpackChunkName: "Team" */ '../views/Team.vue')
    },
    {
        path: '/login',
        name: 'Login',
        meta: { guestOnly: true },
        component: () => import(/* webpackChunkName: "login" */ '../views/auth/Login.vue')
    },
    {
        path: '/register',
        name: 'Register',
        meta: { guestOnly: true },
        component: () => import(/* webpackChunkName: "register" */ '../views/auth/Register.vue')
    }
];

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    scrollBehavior: () => ({ y: 0 }),
    routes
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    if (to.matched.some(record => record.meta.requiresAuth) && !token) {
        next({ path: '/login', query: { redirect: to.fullPath } });
    } else if (to.matched.some(record => record.meta.guestOnly) && token) {
        next({ path: '/' });
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;
