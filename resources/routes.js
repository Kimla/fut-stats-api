import Home from './views/Home.vue'
import Login from './views/auth/Login.vue'
import Register from './views/auth/Register.vue'
import NotFound from './views/NotFound.vue'

export let routes = [
  { path: '/', component: Home, meta: { title: 'Home' } },
  { path: '/login', component: Login, meta: { title: 'Login' } },
  { path: '/register', component: Register, meta: { title: 'Register' } },
  { path: '/:path(.*)', component: NotFound }
]
