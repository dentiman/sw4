<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    bottom
    max-width="240"
    offset-y
    origin="top left"
    right
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
          <span class="font-weight-bold ml-2">{{ label }}</span>
        </span>
      </v-chip>
    </template>
    <v-card :width="240" flat color="falcon-card-bg" rounded="0" >
      <v-container class="py-2 px-3" fluid>
        <v-checkbox
          v-for="(choice,index) in choices"
          :key="index"
          v-model="filterValue"
          :color="checkBoxColor(choice.value)"
          :label="choice.text"
          :value="choice.value"

          class="my-1 red--text"
          hide-details
        ></v-checkbox>
      </v-container>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="reset">reset</v-btn>
        <v-btn color="success" @click="menu = false">OK</v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>

<script>
import formData from '../../../utils/filters/formData'
import { mapActions } from 'vuex'
import { exchangeColors } from '@/utils/filters/helper'
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
      menu: false,
      isCustom: false,
      valueOnOpenMenu: false
    }
  },
  computed: {
    label() {
      if (this.filterValue.length > 1) {
        return `${this.filterValue.length} ${this.$t('filters.labels.selected')}`
      }
      const selectedLabels = []

      this.choices.forEach((choice) => {
        if (this.filterValue.includes(choice.value)) {
          selectedLabels.push(choice.text)
        }
      })

      return selectedLabels.join(', ')
    },
    isClose() {
      return !!(this.filterValue.length)
    },
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
    isEmpty() {
      return !(this.filterValue.length)
    },
    filterData() {
      return formData[this.filterId]
    },
    choices() {
      return this.filterData.choices
    }
  },
  methods: {
    checkBoxColor(choice) {
      if (this.filterId === 'exchange') {
        return exchangeColors[choice]
      }

      return 'primary'
    },
    ...mapActions('screener', ['search']),
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
.v-chip.open {
  border-radius: 14px 14px 0px 0px;
  background-color: var(--falcon-card) !important;
}

.filter-presets-list .v-list-item {
  min-height: 25px !important;
}

.filter-presets-list .v-list-item__action {
  margin: 4px 0 !important;
  margin-right: 28px !important;;
}
</style>
