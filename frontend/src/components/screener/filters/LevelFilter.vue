<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    bottom
    max-width="430"
    offset-y
    origin="top left"
    right
    rounded="b"
    content-class="elevation-1 rounded-tl-0"
  >
    <template v-slot:activator="{ on }">
      <v-chip
        :close="isClose"
        :class="{ 'ma-0 mr-1 mb-1 border': true, 'open': menu}"
        pill
        @click:close="reset()"
        v-on="on"
      >
        <span class="v-label">
          <span class="font-weight-bold text--disabled">{{ $t(`filters.${filterId}.label`) }}</span>
          <span class="font-weight-bold ml-2">{{ buildValueText() }}</span>
        </span>
      </v-chip>
    </template>
    <v-card :width="430">
      <div class="bg-holder bg-card" style="background-image:url('/asset/img/illustrations/corner-1.png')"></div>
      <v-container class="py-0 px-0" fluid >
        <v-toolbar color="transparent" dense flat>
          <v-btn
            v-for="item in choices1"
            :key="item"
            x-small
            :color="priceIsInArray(item)? 'primary': ''"
            class="font-weight-bold"
            @click="togglePriceValue(item)"
          >
            <span style="font-size: 12px">{{ item }}</span>
          </v-btn>
        </v-toolbar>

        <v-toolbar color="transparent" dense flat>
          <v-btn
            v-for="item in choices2"
            :key="item"
            x-small
            :color="priceIsInArray(item)? 'primary': ''"
            class="font-weight-bold"
            @click="togglePriceValue(item)"
          >
            <span style="font-size: 12px">{{ item }}</span>
          </v-btn>
        </v-toolbar>
      </v-container>

      <v-card-actions>

        <v-spacer></v-spacer>
        <v-btn text @click="reset" >reset</v-btn>
        <v-btn color="success" @click="menu = false">OK</v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>

<script>
import formData from '../../../utils/filters/formData'
import { mapActions } from 'vuex'

export default {
  props: {
    filterId: {
      label: '',
      type: String,
      required: true
    }
  },
  data() {
    return {
      choices1: ['95','96','97','98','99','0','1','2','3','4','5'],
      choices2: ['45','46','47','48','49','50','51','52','53','54','55'],
      menu: false,
      isCustom: false,
      valueOnOpenMenu: null
    }
  },
  computed: {
    filterValue: {
      get() {
        return this.$store.state.screener.filters[this.filterId].value
      },
      set(value) {
        this.$store.commit('screener/SET_FILTER', {
          filterId: this.filterId,
          value: value,
          prop: 'value'
        })
      }
    },
    isClose() {
      return !!(this.filterValue.length)
    },
    isEmpty() {
      return !(this.filterValue.length)
    },
    filterData() {
      return formData[this.filterId]
    }

  },
  created() {
  },
  methods: {
    priceIsInArray(value) {
      return this.filterValue.indexOf(value) > -1
    },
    ...mapActions('screener', ['search']),
    togglePriceValue(value) {
      const oldvalue = [...this.filterValue]
      const index = oldvalue.indexOf(value)

      if (index > -1) {
        oldvalue.splice(index,1)
      } else {
        oldvalue.push(value)
      }
      this.filterValue = oldvalue
    },
    buildValueText() {
      if (this.filterValue.length === 0) return ''

      return `${this.filterValue.length} ${this.$t('filters.labels.selected')}`
    },
    reset() {
      this.filterValue = []
      if (this.menu === false) {
        this.search()
      }
      // if was run by button - need to close menu
      this.menu = false
    }

  },
  watch: {
    menu(value) {
      if (value) {
        this.valueOnOpenMenu = this.filterValue
      }
      if (value === false && this.valueOnOpenMenu !== this.filterValue) this.search()
    }
  }
}
</script>

<style>

</style>
