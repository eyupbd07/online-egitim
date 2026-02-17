import { createRouter, createWebHistory } from 'vue-router'
import { routes } from './routes'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

export default function (app) {
  // app.use(router)  <-- BU SATIRI BURADAKİ GİBİ YORUM SATIRI YAP (BAŞINA // KOY)
  // Bu satır çalıştığı için Inertia devre dışı kalıyor ve sayfan beyaz görünüyordu.
}
export { router }