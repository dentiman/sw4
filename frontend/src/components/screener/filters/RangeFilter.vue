<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    bottom
    max-width="290"
    right
    offset-y
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
          <span
            class="font-weight-bold ml-2 "
            :class="labelValueClass"
          >{{ buildValueText({gte: filterValueGte, lte: filterValueLte}) }}</span>
        </span>
      </v-chip>
    </template>
    <v-card
      :width="290"
      flat
      rounded="0"
      class="filter-menu-card"
    >
      <div v-if="showPresets === false" class="bg-holder bg-card" style="background-image:url('/asset/img/illustrations/corner-1.png')"></div>
      <v-container v-if="showPresets" class="py-0 px-0" fluid>
        <v-row>
          <v-col class="pr-0" v-if="positiveChoices.length > 0" :cols="negativeChoices.length > 0 ? '6' : '12'">
            <v-list class="filter-presets-list">
              <v-list-item
                v-for="(choice,index) in positiveChoices"
                :key="index"
                @click="setValueFromPresets(choice)"
              >
                <v-list-item-subtitle :class="colorChange(choice.gte)">{{
                  buildValueText(choice)
                }}
                </v-list-item-subtitle>
              </v-list-item>
            </v-list>
          </v-col>
          <v-col :class="positiveChoices.length > 0 ? 'pl-0' : ''" v-if="negativeChoices.length > 0" :cols="positiveChoices.length > 0 ? '6' : '12'">
            <v-list class="filter-presets-list">
              <v-list-item
                v-for="(choice,index) in negativeChoices"
                :key="index"
                @click="setValueFromPresets(choice)"
              >
                <v-list-item-subtitle :class="colorChange(choice.gte)">{{
                  buildValueText(choice)
                }}
                </v-list-item-subtitle>
              </v-list-item>
            </v-list>
          </v-col>
        </v-row>
      </v-container>

      <v-container v-if="showPresets === false" fluid>
        <v-row>
          <v-col cols="6">
            <v-combobox
              v-model="filterValueGte"
              :items="filterData.choices"
              dense
              label="min"
              outlined
            >
              <!--                                <template slot="item" slot-scope="data">-->
              <!--                                    {{ data.item }} &#45;&#45;&#45;&#45;-->
              <!--                                </template>-->
            </v-combobox>
          </v-col>
          <v-col cols="6">
            <v-combobox
              v-model="filterValueLte"
              :items="filterData.choices"
              dense
              label="max"
              outlined
            ></v-combobox>
          </v-col>
        </v-row>
      </v-container>
      <v-card-actions>
        <v-btn v-if="allowPresets && !showPresets " text @click="isCustom = !isCustom">presets</v-btn>
        <v-btn v-if="allowPresets && showPresets" text @click="isCustom = !isCustom">custom</v-btn>
        <v-spacer></v-spacer>
        <v-btn v-if="showPresets === false" text @click="reset">reset</v-btn>
        <v-btn v-if="showPresets === false" color="success" @click="menu = false">OK</v-btn>
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
      valueOnOpenMenu: {
        gte: null,
        lte: null
      }
    }
  },
  computed: {
    filterValueGte: {
      get() {
        return this.$store.state.screener.filters[this.filterId].gte
      },
      set(value) {
        this.$store.commit('screener/SET_FILTER', {
          filterId: this.filterId,
          value: value,
          prop: 'gte'
        })
      }
    },
    filterValueLte: {
      get() {
        return this.$store.state.screener.filters[this.filterId].lte
      },
      set(value) {
        this.$store.commit('screener/SET_FILTER', {
          filterId: this.filterId,
          value: value,
          prop: 'lte'
        })
      }
    },
    labelValueClass() {
      if (this.filterValueGte && this.filterValueLte) return null

      return this.colorChange(this.filterValueGte)
    },
    showPresets() {
      if (this.allowPresets === false) return false

      return this.isCustom !== true
    },
    allowPresets() {
      return Object.prototype.hasOwnProperty.call(this.filterData, 'presets')
    },
    isClose() {
      return !!(this.filterValueGte || this.filterValueLte)
    },
    isEmpty() {
      return !(this.filterValueGte || this.filterValueLte)
    },
    filterData() {
      return formData[this.filterId]
    },
    choices() {
      return this.filterData.choices
    },
    positiveChoices() {
      return this.filterData.presets.filter((choice) => choice.gte !== null)
    },
    negativeChoices() {
      return this.filterData.presets.filter((choice) => choice.lte !== null)
    }
  },
  methods: {
    ...mapActions('screener', ['search']),
    colorChange(isPositive) {
      return `${isPositive ? 'green_c1' : 'red_c1'}--text`
    },
    setValueFromPresets(presetValue) {
      this.filterValueGte = presetValue.gte
      this.filterValueLte = presetValue.lte
      this.menu = false
    },
    buildValueText(value) {
      if (value.label) return this.$t(value.label)

      if (this.filterData.presets) {
        for (const presetItem of this.filterData.presets) {
          // global.console.log(presetItem)
          if (presetItem.label && value.lte === presetItem.lte && value.gte === presetItem.gte) {
            return this.$t(presetItem.label)
          }
        }
      }

      if (value.gte && value.lte) {
        return this.$t(`filters.${this.filterId}.between`, [this.filterData.humanizeValue(value.gte), this.filterData.humanizeValue(value.lte)])
      }
      if (value.gte) {
        return this.$t(`filters.${this.filterId}.gte`, [this.filterData.humanizeValue(value.gte)])
      }
      if (value.lte) {
        return this.$t(`filters.${this.filterId}.lte`, [this.filterData.humanizeValue(value.lte)])
      }

      return ''
    },
    reset() {
      this.filterValueLte = null
      this.filterValueGte = null
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
        this.valueOnOpenMenu.gte = this.filterValueGte
        this.valueOnOpenMenu.lte = this.filterValueLte
      }
      if (value === false && (
        this.valueOnOpenMenu.gte !== this.filterValueGte ||
        this.valueOnOpenMenu.lte !== this.filterValueLte
      ) ) {   this.search() }
    }
  }
}
</script>

<style>
.border {
  border-width: 1px !important;
}
.filter-menu-card .v-input{

}

.filter-presets-list .v-list-item {
  min-height: 25px !important;
}

.filter-presets-list .v-list-item__action {
  margin: 4px 0 !important;
  margin-right: 28px !important;;
}
</style>
