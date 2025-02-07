import Vue from 'vue'
import { getField, updateField } from 'vuex-map-fields'
// import remove from 'lodash/remove';
import SubmissionError from '@/utils/SubmissionError'
import axios from '@/utils/interceptor'

const handleError = (commit, e) => {
  commit(ACTIONS.TOGGLE_LOADING)

  if (e instanceof SubmissionError) {
    commit(ACTIONS.SET_VIOLATIONS, e.errors)
    // eslint-disable-next-line
        commit(ACTIONS.SET_ERROR, e.errors._error);

    return
  }

  // eslint-disable-next-line
    commit(ACTIONS.SET_ERROR, e.message);
}

const ACTIONS = {
  ADD: 'ADD',
  MERCURE_DELETED: 'MERCURE_DELETED',
  MERCURE_MESSAGE: 'MERCURE_MESSAGE',
  MERCURE_OPEN: 'MERCURE_OPEN',
  RESET_LIST: 'RESET_LIST',
  SET_CREATED: 'SET_CREATED',
  SET_DELETED: 'SET_DELETED',
  SET_ERROR: 'SET_ERROR',
  SET_SELECT_ITEMS: 'SET_SELECT_ITEMS',
  SET_TOTAL_ITEMS: 'SET_TOTAL_ITEMS',
  SET_UPDATED: 'SET_UPDATED',
  SET_VIEW: 'SET_VIEW',
  SET_VIOLATIONS: 'SET_VIOLATIONS',
  TOGGLE_LOADING: 'TOGGLE_LOADING',
  SET_RETRIEVED: 'SET_RETRIEVED'
}

export default function makeCrudModule({
  normalizeRelations = (x) => x,
  resolveRelations = (x) => x,
  namespace
} = {}) {
  return {
    actions: {
      create: ({ commit }, values) => {
        commit(ACTIONS.SET_ERROR, '')
        commit(ACTIONS.TOGGLE_LOADING)

        axios
          .post(namespace, values)
          .then((response) => response.data)
          .then((data) => {
            commit(ACTIONS.TOGGLE_LOADING)
            commit(ACTIONS.ADD, data)
            commit(ACTIONS.SET_CREATED, data)
          })
          .catch((e) => handleError(commit, e))
      },
      del: ({ commit }, item) => {
        commit(ACTIONS.TOGGLE_LOADING)

        axios
          .delete(item['@id'] )
          .then((response) => response.data)
          .then(() => {
            commit(ACTIONS.TOGGLE_LOADING)
            commit(ACTIONS.SET_DELETED, item)
          })
          .catch((e) => handleError(commit, e))
      },
      fetchAll: ({ commit, state }, params) => {
        let request = namespace

        if (params) {
          const query = Object.keys(params)
            .map((key) => `${key}=${params[key]}`)
            .join('&')

          request = request + '?' + query
        }

        axios
          .get(request)
          .then((response) => response.data)
          .then((retrieved) => {
            commit(
              ACTIONS.SET_TOTAL_ITEMS,
              retrieved['hydra:totalItems']
            )
            commit(ACTIONS.SET_VIEW, retrieved['hydra:view'])

            if (true === state.resetList) {
              commit(ACTIONS.RESET_LIST)
            }

            retrieved['hydra:member'].forEach((item) => {
              commit(ACTIONS.ADD, normalizeRelations(item))
            })
          })
          .catch((e) => handleError(commit, e))
      },

      load: ({ commit }, id) => {

        commit(ACTIONS.TOGGLE_LOADING)
        axios
          .get( id)
          .then((response) => response.data)
          .then((item) => {
            commit(ACTIONS.TOGGLE_LOADING)
            commit(ACTIONS.ADD, normalizeRelations(item))
            commit(ACTIONS.SET_RETRIEVED, item)
          })
          .catch((e) => handleError(commit, e))
      },
      update: ({ commit }, item) => {
        commit(ACTIONS.SET_ERROR, '')
        commit(ACTIONS.TOGGLE_LOADING)

        axios
          .put(item['@id'],item)
          .then((response) => response.data)
          .then((data) => {
            commit(ACTIONS.TOGGLE_LOADING)
            commit(ACTIONS.ADD, data)
            commit(ACTIONS.SET_UPDATED, data)
          })
          .catch((e) => handleError(commit, e))
      }
    },
    getters: {
      find: (state) => (id) => {
        //  global.console.log(id);
        return resolveRelations(state.byId[id])
      },
      getField,
      list: (state, getters) => {
        return state.allIds.map((id) => getters.find(id))
      }
    },
    mutations: {
      updateField,
      [ACTIONS.ADD]: (state, item) => {
        Vue.set(state.byId, item['@id'], item)
        if (state.allIds.includes(item['@id'])) return
        state.allIds.push(item['@id'])
      },
      [ACTIONS.RESET_LIST]: (state) => {
        Object.assign(state, {
          allIds: [],
          byId: {},
          resetList: false
        })
      },
      [ACTIONS.SET_CREATED]: (state, created) => {
        Object.assign(state, { created })
      },
      [ACTIONS.SET_DELETED]: (state, deleted) => {
        if (!state.allIds.includes(deleted['@id'])) return
        delete(state.byId[deleted['@id']])
        Object.assign(state, {
          allIds: state.allIds.filter((item) => item['@id'] !== deleted['@id']),
          // byId: state.byId.filter((item ,index) => index !== deleted['@id']),
          // byId: remove(state.byId, id => id === deleted['@id']),
          deleted
        })
      },
      [ACTIONS.SET_ERROR]: (state, error) => {
        Object.assign(state, { error, isLoading: false })
      },
      [ACTIONS.SET_SELECT_ITEMS]: (state, selectItems) => {
        Object.assign(state, {
          error: '',
          isLoading: false,
          selectItems
        })
      },
      [ACTIONS.SET_TOTAL_ITEMS]: (state, totalItems) => {
        Object.assign(state, { totalItems })
      },
      [ACTIONS.SET_UPDATED]: (state, updated) => {
        Object.assign(state, { updated })
      },
      [ACTIONS.SET_VIEW]: (state, view) => {
        Object.assign(state, { view })
      },
      [ACTIONS.SET_VIOLATIONS]: (state, violations) => {
        Object.assign(state, { violations })
      },
      [ACTIONS.TOGGLE_LOADING]: (state) => {
        Object.assign(state, { error: '', isLoading: !state.isLoading })
      },
      [ACTIONS.SET_RETRIEVED]: (state, retrieved) => {
        Object.assign(state, { retrieved: retrieved })
      }
    },
    namespaced: true,
    state: {
      allIds: [],
      byId: {},
      created: null,
      retrieved: null,
      deleted: null,
      error: '',
      isLoading: false,
      resetList: false,
      selectItems: null,
      totalItems: 0,
      updated: null,
      view: null,
      violations: null
    }
  }
}
