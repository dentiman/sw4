import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import MainTemplate from '@/layouts/MainTemplate'
import ScreenerView from '@/pages/ScreenerView'
import Register from '@/pages/users/Register'
import ResetPassword from '@/pages/users/ResetPassword'
import ResetPasswordFinish from '@/pages/users/ResetPasswordFinish'
import CentredTemplate from '@/layouts/CentredLayout'
import Demo from '@/components/Demo'
import BuyPremium from '@/pages/premium/buy'
import Premium from '@/pages/premium/premium'
import Policy from '@/pages/premium/policy'
import Terms from '@/pages/premium/terms'
import About from '@/pages/premium/about'
Vue.use(Router)
const authorizedOnlyRoutes = [
  'GoBuyPremium'
]

export const routes = [{
  path: '/',
  component: () => import(/* webpackChunkName: "layout-default" */ '@/layouts/DefaultLayout.vue'),
  children: [
    {
      path: '/',
      name: 'StockWatcher',
      component:  MainTemplate,
      children: [
        {
          path: '/',
          name: 'Screener',
          component:  ScreenerView
        },
        {
          path: '/demo',
          name: 'Demo',
          component:  Demo
        },
        {
          path: '/quote/:ticker',
          name: 'Quote',
          component: ScreenerView
        }
      ]
    },
    {
      path: '/',
      name: 'centered',
      component: () => import(/* webpackChunkName: "starter-page" */ '@/layouts/CentredLayout'),
      children: [
        {
          path: '/register',
          name: 'Register',
          component:  Register
        },
        {
          path: 'login',
          name: 'Login',
          component: () => import(/* webpackChunkName: "starter-page" */ '@/pages/users/Login')
        },
        {
          path: 'reset',
          name: 'ResetPassword',
          component: () => import(/* webpackChunkName: "starter-page" */ '@/pages/users/ResetPassword')
        },
        {
          path: '/reset/validate/:token',
          name: 'ResetPasswordFinish',
          component: () => import(/* webpackChunkName: "starter-page" */ '@/pages/users/ResetPasswordFinish')
        }
      ]
    },
    {
      path: '/',
      name: 'premium',
      component: () => import(/* webpackChunkName: "starter-page" */ '@/layouts/DefaultLayout.vue'),
      children: [
        {
          path: '/about',
          name: 'About',
          component:  About
        },
        {
          path: '/premium',
          name: 'BuyPremium',
          component:  Premium
        },
        {
          path: '/buy',
          name: 'GoBuyPremium',
          component:  BuyPremium
        },
        {
          path: '/policy',
          name: 'Policy',
          component:  Policy
        },
        {
          path: '/terms',
          name: 'Terms',
          component:  Terms
        }
      ]
    }

  ]
}, {
  path: '*',
  component: () => import(/* webpackChunkName: "layout-error" */ '@/layouts/ErrorLayout.vue'),
  children: [{
    path: '',
    name: 'error',
    component: () => import(/* webpackChunkName: "error" */ '@/pages/error/NotFoundPage.vue')
  }]
}]

const router = new Router({
  mode: 'history',
  base: process.env.BASE_URL || '/',
  scrollBehavior(to, from, savedPosition) {
    return { x: 0, y: 0 }
  },
  routes
})

/**
 * Before each route update
 */
router.beforeEach((to, from, next) => {
  const token = store.getters['auth/jwtDecoded'] || null
  const authorized = token && token.exp > Date.now() / 1000

  if ( authorizedOnlyRoutes.includes(to.name) && !authorized) {
    return  next({ name: 'Login',  query: { redirect: to.path } })
  } else {
    return  next()
  }
})

/**
 * After each route update
 */
router.afterEach((to, from) => {
})

export default router
