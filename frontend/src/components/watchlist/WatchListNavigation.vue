<template>
  <div>
    <v-dialog
      v-model="showDialog"
      :transition="false"
      max-width="700"
      persistent
    >
      <v-card :disabled="isLoading" :loading="isLoading">
        <v-card-title size="16">
          {{ $t('watchlist.continue.subtitle') }} {{ watchlistCreatedAt }}.<br> {{ $t('watchlist.continue.title') }}
        </v-card-title>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            @click="createNew"
          >
            Create New Watchlist
          </v-btn>
          <v-btn
            color="success"
            @click="continueCurrentToday"
          >
            Continue
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-card :loading="isLoading" flat color="transparent">
      <v-toolbar
        v-if="primaryDrawerOn === false"
        color="transparent"
        flat
        height="34"
      >
        <a style="white-space: nowrap;" @click="listTab = 'lists'" >{{ $t('watchlist.label_s') }}</a>
        <v-icon v-if="listTab === 'items'">mdi-chevron-right</v-icon>
        <a v-if="editMode === false && listTab === 'items'" @click="editMode = true">{{ name }}</a>

        <v-text-field
          v-if="editMode && listTab === 'items'"
          id="watchlist_name_input"
          v-model="name"
          :rules="nameRules"
          maxlength="12"
          class="font-weight-medium mx-0 "
          color="success"
          dense
          flat
          hide-details
          autofocus
          style=" min-width: 122px; max-width: 122px;font-size: 16px"
        ></v-text-field>

        <v-spacer></v-spacer>
        <v-btn v-if="editMode && listTab === 'items'" icon small @click="saveCurrent" >
          <v-icon color="success" size="20">mdi-content-save-edit</v-icon>
        </v-btn>

        <v-menu
          v-if="editMode===false && listTab === 'items'"
          min-width="220"
          left
          rounded="0"
          content-class="elevation-1 rounded-tl-0"
          :disabled="listTab === 'lists' || isEmpty"
          offset-y
          nudge-right="32"
          nudge-bottom="2"
        >
          <template v-slot:activator="{ on }">
            <v-btn icon small v-on="on">
              <v-icon size="20">mdi-export</v-icon>
            </v-btn>

          </template>
          <v-list dense>
            <v-subheader><h4 class="pl-1">{{ $t('label.view_in_screener') }}</h4></v-subheader>
            <v-divider class="my-0"></v-divider>
            <v-list-item @click="viewInScreenerAll()">
              <v-list-item-icon>
                <v-icon size="18">mdi-export</v-icon>
              </v-list-item-icon>
              <v-list-item-title v-text="$t('watchlist.allTicker')"></v-list-item-title>
            </v-list-item>
            <div
              v-for="wlItem in watchlistData"
              :key="wlItem.label"
            >
              <v-list-item v-if="wlItem.tickers.length" @click="setFilters(wlItem.tickers)">
                <v-list-item-icon>
                  <v-icon :color="wlItem.color" size="18">mdi-export</v-icon>
                </v-list-item-icon>
                <v-list-item-title v-text="$t(`watchlist.${wlItem.label}_s`)"></v-list-item-title>
              </v-list-item>
            </div>

          </v-list>
        </v-menu>

        <v-menu
          v-if="editMode===false && listTab === 'items'"
          v-model="copyMenuIsOpen"
          min-width="220"
          :close-on-content-click="false"
          left
          rounded="0"
          content-class="elevation-1 rounded-tl-0"
          :disabled="listTab === 'lists' || isEmpty"
          offset-y
          nudge-right="4"
          nudge-bottom="3"
        >
          <template v-slot:activator="{ on }">
            <v-btn icon small v-on="on">
              <v-icon size="20"> mdi-content-copy</v-icon>
            </v-btn>

          </template>
          <v-list dense>
            <v-subheader><h4 class="pl-1">{{ $t('copy-clipboard') }}</h4></v-subheader>
            <v-divider class="my-0"></v-divider>
            <v-list-group >
              <template v-slot:activator>
                <v-list-item-icon>
                  <v-icon size="18">mdi-content-copy</v-icon>
                </v-list-item-icon>
                <v-list-item-title v-text="$t(`watchlist.allTicker`)"></v-list-item-title>
              </template>
              <v-list-item @click="copyToClipboardAll('\n')">
                <v-list-item-title v-text="$t('label.copy.vertical')"></v-list-item-title>
              </v-list-item>
              <v-list-item @click="copyToClipboardAll(' ')">
                <v-list-item-title v-text="$t('label.copy.inline')"></v-list-item-title>
              </v-list-item>
            </v-list-group>

            <div
              v-for="wlItem in watchlistData"
              :key="wlItem.label"
            >
              <v-list-group
                v-if="wlItem.tickers.length"
                :color="wlItem.color"
              >
                <template v-if="wlItem.tickers" v-slot:activator>
                  <v-list-item-icon>
                    <v-icon :color="wlItem.color" size="18">mdi-content-copy</v-icon>
                  </v-list-item-icon>
                  <v-list-item-title v-text="$t(`watchlist.${wlItem.label}_s`)"></v-list-item-title>
                </template>
                <v-list-item @click="copyToClipboard(wlItem.tickers.join('\n'))">
                  <v-list-item-title v-text="$t('label.copy.vertical')"></v-list-item-title>
                </v-list-item>
                <v-list-item @click="copyToClipboard(wlItem.tickers.join(' '))">
                  <v-list-item-title v-text="$t('label.copy.inline')"></v-list-item-title>
                </v-list-item>
              </v-list-group>
            </div>

          </v-list>
        </v-menu>
      </v-toolbar>
      <v-divider></v-divider>

      <v-tabs-items v-model="listTab">
        <v-tab-item value="lists">
          <v-card :disabled="allListsLoading" :loading="allListsLoading" flat>
            <v-list dense class="py-0" >
              <v-list-item style="min-height: 28px !important;" @click="createNewAndGoToItems" >
                <v-list-item-icon class="my-0"><v-icon color="success">mdi-plus</v-icon></v-list-item-icon>
                <v-list-item-title>{{ $t('watchlist.create') }}</v-list-item-title>
              </v-list-item>
            </v-list>
            <v-list class="py-0" dense>
              <div
                v-for="(wlItem, index) in watchlists"
                :key="index"
                class="ticker-list-row"
              >
                <div style="position: absolute;margin-left: 273px;z-index: 10;display: inline ">
                  <v-menu
                    min-width="180"
                    left
                    rounded="0"
                    content-class="elevation-1 rounded-tl-0"
                    offset-y
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn
                        v-bind="attrs"
                        class="ticker-list-action"
                        small
                        icon
                        v-on="on"
                      >
                        <v-icon color="grey lighten-1">mdi-window-close</v-icon>
                      </v-btn>
                    </template>
                    <v-list dense>
                      <v-list-item color="error" @click="deleteListAndResetSelected(wlItem)" >
                        <v-list-item-icon>
                          <v-icon color="error" size="18">mdi-close</v-icon>
                        </v-list-item-icon>
                        <v-list-item-title>{{ $t('Delete') }}</v-list-item-title>
                      </v-list-item>
                    </v-list>
                  </v-menu>
                </div>
                <v-list-item
                  :class="id === wlItem['@id'] ? 'v-list-item--highlighted' : ''"
                  style="min-height: 28px !important;"
                  @click="chooseItemFromList(wlItem)"
                >
                  <v-list-item-icon class="my-0"><v-icon>mdi-record</v-icon></v-list-item-icon>
                  <v-list-item-title>{{ wlItem.name }}</v-list-item-title>
                  <v-list-item-action class="my-0">
                    <span class="mr-5">
                      <span
                        v-if="wlItem.green.length"
                        :class="`success--text ${colorAccent} mr-1`"
                        style="display: inline;"
                      >{{ wlItem.green.length }}</span>
                      <span
                        v-if="wlItem.red.length"
                        :class="`error--text ${colorAccent} mr-1`"
                        style="display: inline;"
                      >{{ wlItem.red.length }}</span>
                      <span
                        v-if="wlItem.blue.length"
                        :content="wlItem.blue.length"
                        :class="`primary--text ${colorAccent}`"
                        style="display: inline;"
                      >{{ wlItem.blue.length }}</span>
                    </span>
                  </v-list-item-action>
                </v-list-item>
              </div>

            </v-list>
            <v-pagination
              v-if="allListsTotalItems > 20"
              v-model="allListsPage"
              :length="Math.ceil(allListsTotalItems / 20)"
              :total-visible="7"
              class="mx-auto"
            ></v-pagination>

          </v-card>
        </v-tab-item>
        <v-tab-item value="items">
          <v-responsive v-if="isEmpty && primaryDrawerOn === false" max-width="300" class="mx-auto text-center">
            <div class="mb-1">
              <v-icon class="text-h1 text--disabled" >mdi-folder-outline</v-icon>
            </div>
            <div class="font-weight-regular mt-1 mb-2 text--disabled">{{ $t('watchlist.empty.isEmpty') }}</div>
          </v-responsive>
          <div v-if="isEmpty === false">
            <div v-for="wlItem in watchlistData" :key="wlItem.label">
              <v-list class="py-0" dense>
                <div
                  v-for="(quote, i) in wlItem.quotes"
                  :key="i"
                  class="ticker-list-row"
                >
                  <div style="position: absolute;margin-left: 273px;z-index: 10;display: inline ">
                    <v-menu
                      min-width="180"
                      left
                      rounded="0"
                      content-class="elevation-1 rounded-tl-0"
                      offset-y
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn
                          v-bind="attrs"
                          class="ticker-list-action"
                          small
                          icon
                          v-on="on"
                        >
                          <v-icon color="grey lighten-1">mdi-window-close</v-icon>
                        </v-btn>
                      </template>
                      <v-list dense>

                        <v-list-item color="error" @click="remove(quote.id)">
                          <v-list-item-icon>
                            <v-icon color="error" size="18">mdi-close</v-icon>
                          </v-list-item-icon>
                          <v-list-item-title>{{ $t('Delete') }}</v-list-item-title>
                        </v-list-item>
                        <v-list-item :color="wlItem.moveNext" @click="move({color: wlItem.moveNext,ticker: quote.id})">
                          <v-list-item-icon>
                            <v-icon :color="wlItem.moveNext" size="18">mdi-arrow-right-bold</v-icon>
                          </v-list-item-icon>
                          <v-list-item-title>{{ $t(`watchlist.move.${wlItem.moveNext}`) }}</v-list-item-title>
                        </v-list-item>
                        <v-list-item :color="wlItem.moveBack" @click="move({color: wlItem.moveBack,ticker: quote.id})">
                          <v-list-item-icon>
                            <v-icon :color="wlItem.moveBack" size="18">mdi-arrow-right-bold</v-icon>
                          </v-list-item-icon>
                          <v-list-item-title>{{ $t(`watchlist.move.${wlItem.moveBack}`) }}</v-list-item-title>
                        </v-list-item>
                      </v-list>
                    </v-menu>
                  </div>
                  <v-list-item
                    :to="`/quote/${quote.id}`"
                    class="px-1 pl-1 "
                    style="min-height: 28px !important;"
                  >
                    <v-list-item-content class="py-0 pl-1 ">
                      <v-list-item-title v-if="quote.id">
                        <v-container class="pa-0">
                          <v-row no-gutters>
                            <v-col class="py-0" >
                              <quote-field-value
                                :exchange="quote['exchange']"
                                :field-value="quote['id']"
                                :index="quote['index']"
                                :prevent-click="false"
                                field-id="id"
                              >
                              </quote-field-value>
                            </v-col>
                            <v-col v-if="primaryDrawerOn === false" class="py-0 px-1">
                              <quote-field-value
                                :exchange="quote['exchange']"
                                :field-value="quote['price']"
                                :index="quote['index']"
                                field-id="price"
                              >
                              </quote-field-value>
                            </v-col>
                            <v-col v-if="primaryDrawerOn === false" class="py-0 px-1" >
                              <quote-field-value
                                :exchange="quote['exchange']"
                                :field-value="quote['ch']"
                                :index="quote['index']"
                                field-id="ch"
                              >
                              </quote-field-value>
                            </v-col>
                            <v-col v-if="primaryDrawerOn === false" class="py-0 px-1">
                              <quote-field-value
                                :exchange="quote['exchange']"
                                :field-value="quote['chp']"
                                :index="quote['index']"
                                field-id="chp"
                              >
                              </quote-field-value>
                            </v-col>
                          </v-row>
                        </v-container>
                      </v-list-item-title>
                    </v-list-item-content>
                    <div :class="`v-alert__border v-alert__border--left ${wlItem.color} ${colorAccent}  v-alert__border--has-color`"></div>
                  </v-list-item>
                </div>
                <v-divider class="my-0"></v-divider>
              </v-list>
            </div>
          </div>
        </v-tab-item >

      </v-tabs-items>
    </v-card>
  </div>
</template>

<script>
import QuoteFieldValue from '@/components/quote/QuoteFieldValue'
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import moment from 'moment'
import store from '@/store'
import filterSerialize from '@/utils/filters/filterSerialize'
export default {
  name: 'WatchListNavigation',
  components: {
    QuoteFieldValue
  },
  data() {
    return {
      errors: [],
      nameRules: [
        (v) => !!v || 'This field is required',
        (v) => ( v && v.length >= 2 ) || 'This field must have atleast 2 characters',
        (v) => ( v && v.length <= 12 ) || 'This field exceeds maximum allowed characters'
      ],
      copyMenuIsOpen: false,
      listTab: 'items',
      allListsPage: 1,
      chooseMode: false,
      editMode: false
    }
  },
  computed: {
    colorAccent() {
      return this.$vuetify.theme.dark ? 'darken-1' : 'lighten-1'
    },
    ...mapFields('auth', {
      primaryDrawerOn: 'profile.primaryDrawerOn'
    }),
    watchlistCreatedAt() {
      if (this.createdAt) return moment(this.createdAt).format('YYYY-MM-DD')

      return null
    },
    watchlists() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    isEmpty() {
      return this.watchlistData[0].tickers.length === 0 &&
        this.watchlistData[1].tickers.length === 0 &&
        this.watchlistData[2].tickers.length === 0
    },
    watchlistData() {
      const greenQuotes = []
      const redQuotes = []
      const blueQuotes = []

      this.quotes.forEach((item) => {
        if (this.green.includes(item.id)) {
          greenQuotes.push(item)
        }
        if (this.red.includes(item.id)) {
          redQuotes.push(item)
        }
        if (this.blue.includes(item.id)) {
          blueQuotes.push(item)
        }
      })

      return [
        { tickers: this.green, label: 'green', quotes: greenQuotes, moveBack: 'blue', moveNext: 'red', color: 'success' },
        { tickers: this.red, label: 'red', quotes: redQuotes, moveBack: 'green', moveNext: 'blue', color: 'error' },
        { tickers: this.blue, label: 'blue', quotes: blueQuotes, moveBack: 'red', moveNext: 'green', color: 'primary' }
      ]
    },
    greenInput() {
      return this.green.join(' ')
    },
    redInput() {
      return this.red.join(' ')
    },
    blueInput() {
      return this.blue.join(' ')
    },
    ...mapGetters('watchlist', ['askToContinue']),
    ...mapGetters('watchlists', ['list', 'find']),
    ...mapState('watchlists',
      {
        deletedList: 'deleted',
        createdList: 'created',
        allListsLoading: 'isLoading',
        allListsTotalItems: 'totalItems'
      }
    ),
    ...mapState('watchlist', ['createdAt', 'isLoading', 'error', 'quotes', 'id']),
    ...mapFields('watchlist', ['name', 'green', 'red', 'blue', 'showDialog']),
    ...mapFields('watchlists', ['resetList']),
    ...mapFields('auth', { watchlistId: 'profile.watchlistId', activeFilterPresetId: 'profile.activeFilterPresetId' })

  },
  methods: {
    viewInScreenerAll() {
      const tickers = []

      this.watchlistData.forEach((wlItem) => {
        tickers.push(wlItem.tickers.join(' '))
      })
      this.setFilters(tickers)
    },
    setFilters(tickers) {
      this.activeFilterPresetId = null
      this.$store.commit('screener/SET_FILTERS', { ...filterSerialize.getDefaultFilters(), ...{ id: { selected: true, value: tickers.join(' ') } } })
      this.search()
    },
    deleteListAndResetSelected(listItem) {
      if (this.watchlistId ===  listItem['@id']) {
        this.$store.commit('watchlist/CLEAR_All_WATCHLIST')
        this.watchlistId =  null
      }
      this.deleteList(listItem)
    },
    createNewAndGoToItems() {
      this.createNew()
      this.listTab = 'items'
      this.editMode = true
    },
    saveCurrent() {
      this.saveList()
      setTimeout(() => this.fetchAll(),1000)
      this.editMode = false
    },
    chooseItemFromList(item) {
      this.watchlistId =  item['@id']
      //  this.loadList(item['@id'])
      this.listTab = 'items'
    },
    createNew() {
      this.$store.commit('watchlist/CLEAR_All_WATCHLIST')
      this.toggleTicker(this.$store.state.watchlist.continueWidthTicker)
      this.watchlistId =  null
      this.showDialog = false
    },
    continueCurrentToday() {
      this.continueCurrentWatchlistToday()
      this.toggleTicker(this.$store.state.watchlist.continueWidthTicker)
      this.showDialog = false
    },
    copyToClipboardAll(separator) {
      const tickers = []

      this.watchlistData.forEach((wlItem) => {
        tickers.push(wlItem.tickers.join(separator))
      })

      this. copyToClipboard(tickers.join(separator))
    },
    copyToClipboard(text) {
      navigator.clipboard.writeText(text)
      store.commit('notifications/push',{ text: 'Copied to clipboard',  type: 'success' } )
    },
    ...mapActions('watchlists', {
      fetchAll: 'fetchAll',
      deleteList: 'del'
    }),
    ...mapActions('watchlist', {
      continueCurrentWatchlistToday: 'continueCurrentWatchlistToday',
      toggleTicker: 'toggleTicker',
      saveList: 'save',
      loadList: 'load',
      clearList: 'clearList',
      move: 'move',
      remove: 'remove',
      loadQuotes: 'loadQuotes'
    }),
    ...mapActions('screener', ['search'])
  },
  watch: {
    watchlistId(value) {
      if (value) {
        this.loadList(this.watchlistId)
      }
    },
    primaryDrawerOn(value) {
      if (value === true) {
        this.listTab = 'items'
      }
    },
    listTab(value) {
      if (value === 'lists') {
        this.allListsPage = 1
        this.resetList = true
        this.fetchAll({ page: this.allListsPage })
      }
    },
    deletedList() {
      this.fetchAll({ page: this.allListsPage })
    },
    id(value) {
      if (value) {
        this.watchlistId = value
      }
    },
    allListsPage() {
      this.resetList = true
      this.fetchAll({ page: this.allListsPage })
    }
  },
  created() {
    // if (this.watchlistId) {
    //   this.loadList(this.watchlistId)
    // }
    this.fetchAll()
  }
}
</script>

<style>
.ticker-list-row .ticker-list-action {
  display: none;
}
.ticker-list-row:hover .ticker-list-action {
  display: block;
}

.ticker-list-action {
  opacity: .6;
}
.ticker-list-action:hover {
  opacity: 1;
}
#watchlist_name_input {
  padding-bottom: 5px;
  padding-left: 5px;
}

#choose-list .v-list-item__action--stack {
  flex-direction: row;
  min-width: auto;
}

</style>
