import Vue from 'vue'
import Vuex from 'vuex'

// Global vuex
import AppModule from './app'
import notifications from './notifications'
import auth from './auth'
import screener from './screener'
import watchlist from './watchlist'
import quote from './quote'
import makeCrudModule from './crud'
Vue.use(Vuex)

/**
 * Main Vuex Store
 */
const store = new Vuex.Store({
  modules: {
    app: AppModule,
    auth,
    notifications,
    screener,
    quote,
    watchlist,
    panel_layouts: makeCrudModule({
      namespace: 'api/panel_layouts'
    }),
    table_layouts: makeCrudModule({
      namespace: 'api/table_layouts'
    }),
    screener_filters: makeCrudModule({
      namespace: 'api/screener_filters'
    }),
    watchlists: makeCrudModule({
      namespace: 'api/watchlists'
    })
  }
})

export default store
