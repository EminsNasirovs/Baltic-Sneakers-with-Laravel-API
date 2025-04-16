import './assets/main.css'

import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { autoAnimatePlugin } from '@formkit/auto-animate/vue'
import App from './App.vue'
import Home from './pages/Home.vue'
import Favourites from './pages/Favourites.vue'

import Login from './pages/Login.vue'
import Register from './pages/Register.vue'
import Profile from './pages/Profile.vue'
import Orders from './pages/Orders.vue'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/favourites', name: 'Favourites', component: Favourites },
  { path: '/login', name: 'Login', component: Login},
  { path: '/register', name: 'Register', component: Register},
  { path: '/profile', name: 'Profile', component: Profile},
  { path: '/:pathMatch(.*)*', redirect: '/' }, 
  { path: '/orders',  name: 'Orders', component: Orders }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const app = createApp(App)
app.use(router)
app.use(autoAnimatePlugin)

app.mount('#app')
