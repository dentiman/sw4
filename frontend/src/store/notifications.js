import Vue from 'vue'

const pushAlter = (state,alert) =>  {
  const id = Date.now()

  Vue.set(state.alerts,id, { id:id, ...alert })
}

export default {
  namespaced: true,
  getters: {
    list: (state) => {
      const list = []
      // eslint-disable-next-line
      for (let [id, item] of Object.entries(state.alerts)) {
        list.push(item)
      }

      return list
    }
  },
  state: {
    alerts: {}
  },
  mutations: {
    remove: (state,id) => {
      Vue.delete(state.alerts,id)
    },
    error: (state, text) => {
      pushAlter(state,{ text: text, type: 'error' })
    },
    push: (state, item) => {
      pushAlter(state,item)
    }
  }
}
