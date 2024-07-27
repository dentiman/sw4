<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    bottom
    offset-y
    max-width="290"
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
          <span class="font-weight-bold ml-2">{{ buildValueText(filterValue) }}</span>
        </span>
      </v-chip>
    </template>
    <v-card :width="290">
      <v-container v-if="showPresets" class="py-0 px-0" fluid>
        <v-list class="filter-presets-list">
          <v-list-item
            v-for="(choice,index) in choices"
            :key="index"
            @click="setValueFromPresets(choice)"
          >
            <v-list-item-subtitle>{{ buildValueText(choice) }}</v-list-item-subtitle>
          </v-list-item>
        </v-list>
      </v-container>

      <v-container v-if="showPresets === false" class="py-0 px-0" fluid>
        <v-date-picker
          v-model="normalizedValue"
          class="earnings-picker"
          flat
          no-title
          range
        ></v-date-picker>
      </v-container>
      <v-card-actions>
        <v-btn v-if="allowPresets && !showPresets " text @click="isCustom = !isCustom">simple</v-btn>
        <v-btn v-if="allowPresets && showPresets" text @click="isCustom = !isCustom">custom</v-btn>
        <v-spacer></v-spacer>
        <v-btn v-if="showPresets === false" text @click="reset">reset</v-btn>
        <v-btn v-if="showPresets === false" color="primary" text @click="menu = false">Save</v-btn>
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
      menu: false,
      isCustom: false,
      valueOnOpenMenu: null
    }
  },
  computed: {
    filterValue: {
      get() {
        return this.$store.state.screener.filters[this.filterId].dates
      },
      set(value) {
        this.$store.commit('screener/SET_FILTER', {
          filterId: this.filterId,
          value: value,
          prop: 'dates'
        })
      }
    },
    normalizedValue: {
      get() {
        const showDates = []

        this.filterValue.forEach((date) => {
          showDates.push(this.filterData.unHumanizeValue(date))
        })

        return showDates
      },
      set(val) {
        this.filterValue = val
      }
    },
    showPresets() {
      if (this.allowPresets === false) return false

      return this.isCustom !== true
    },
    allowPresets() {
      return Object.prototype.hasOwnProperty.call(this.filterData, 'presets')
    },
    isClose() {
      return !!(this.filterValue.length)
    },
    isEmpty() {
      return !(this.filterValue.length)
    },
    filterData() {
      return formData[this.filterId]
    },
    choices() {
      return this.filterData.presets
    }
  },
  created() {
  },
  methods: {
    ...mapActions('screener', ['search']),
    setValueFromPresets(presetValue) {
      this.filterValue = presetValue
      this.menu = false
    },
    buildValueText(dates) {
      const showDates = []

      dates.forEach((date) => {
        showDates.push(this.filterData.unHumanizeValue(date))
      })

      return showDates.join(' ~ ')
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
.filter-presets-list .v-list-item {
  min-height: 25px !important;
}

.filter-presets-list .v-list-item__action {
  margin: 4px 0 !important;
  margin-right: 28px !important;;
}

.earnings-picker {
  webkit-box-shadow: none;
  box-shadow: none;
}
</style>
