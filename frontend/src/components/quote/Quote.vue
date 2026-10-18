<template>
  <v-tabs-items v-model="quoteWindow" style="background-color: transparent">
    <v-tab-item value="quote-window-chart" :reverse-transition="false" :transition="false">
      <quote-charts-cart :width="chartWidth" :ticker="ticker"></quote-charts-cart>
    </v-tab-item>
    <v-tab-item value="quote-window-info" :reverse-transition="false" :transition="false">
      <v-container style="max-width: 1200px" class="pa-0 toolbar-bg">
        <v-card v-if="quote" class="mt-4" min-height="70">
          <div class="bg-holder bg-card" style="background-image:url('/asset/img/illustrations/corner-1.png')"></div>
          <v-card-actions >
            <v-list-item two-line>
              <v-list-item-content>
                <v-list-item-title size="28" class="headline text_light--text">
                  {{ quote.name }}
                </v-list-item-title>
                <v-list-item-subtitle>
                  {{ quote.sector ? `${quote.sector}` : '' }}
                  {{ quote.ind ? `(${quote.ind})` : '' }}
                  <quote-field-value
                    v-if="quote.exchange"
                    field-id="exchange"
                    :field-value="quote.exchange"
                    :exchange="quote.exchange"
                    :index="quote.index"
                  >
                  </quote-field-value>
                  {{ quote.exchange ? ',' : '' }}
                  {{ quote.country }}
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-card-actions>
        </v-card>
        <v-row >
          <v-col
            v-for="(group, index) in statistic"
            :key="index"
            cols="4"
            :class="index === 0 ? 'pr-1 pl-3' : (index === 1 ? 'px-0' : 'pl-1 pr-3' ) "
          >
            <v-card class="mt-1">
              <v-card-title >{{ $t(group.label) }}</v-card-title>
              <v-list v-if="quote" class="transparent" dense>
                <v-list-item
                  v-for="fieldId in group.items"
                  :key="fieldId"
                >
                  <v-list-item-content >
                    <v-list-item-title class="text_light--text">{{ $t(`filters.${fieldId}.label`) }}</v-list-item-title>
                    <v-list-item-subtitle class="text--disabled">{{ $t(`filters.${fieldId}.description`) }}</v-list-item-subtitle>
                  </v-list-item-content>

                  <v-list-item-action>
                    <quote-field-value
                      :field-id="fieldId"
                      :field-value="quote[fieldId]"
                      :exchange="quote.exchange"
                      :index="quote.index"
                    >
                    </quote-field-value>
                  </v-list-item-action>
                </v-list-item>
              </v-list>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-tab-item>
  </v-tabs-items>
</template>

<script>

import { mapFields } from 'vuex-map-fields'
import { mapActions, mapState } from 'vuex'
import axios from '@/utils/interceptor'
import { exchanges } from '@/utils/filters/helper'
import QuoteFieldValue from '@/components/quote/QuoteFieldValue'
import QuoteChartsCart from '@/components/quote/QuoteChartsCart'

export default {
  name: 'Quote',
  components: {
    QuoteFieldValue,
    QuoteChartsCart
  },
  props: {},
  data() {
    return {
      statistic: [
        { label: 'quote.group.basic', items:  [
          'price',
          'ch',
          'chp',
          'chpo',
          'vol',
          'marketCap',
          'atr',
          'relativeVolume'
        ] },
        { label: 'quote.group.tech', items:  [
          'priceRange',
          'gep',
          'averageDailyVolume10Day',
          'averageDailyVolume3Month',
          'fiftyDayAverageChangePercent',
          'twoHundredDayAverageChangePercent',
          'fiftyTwoWeekHighChangePercent',
          'fiftyTwoWeekLowChangePercent'
        ] },
        { label: 'quote.group.other', items:  [
          'pvol',
          'pch',
          'pchp',
          'forwardPE',
          'sharesOutstanding',
          'earn',
          'ipo',
          'ttime'
        ] }
      ],
      chartWidth: 1200,
      fullscreenMode: false,
      isLoading: false,
      quote: null
    }
  },
  computed: {
    chartTitle() {
      if (!this.quote) return ''
      let title = this.quote.id

      if (this.quote.exchange && exchanges[this.quote.exchange]) {
        title = title + ', ' + exchanges[this.quote.exchange]
      }

      return title
    },
    isReady() {
      return !!(this.isLoading === false && this.quote)
    },
    ...mapState('auth', ['ticker']),
    ...mapFields('auth', {
      primaryDrawerOn: 'profile.primaryDrawerOn',
      quoteWindow: 'quoteWindow'
    })
  },
  methods: {
    ...mapActions('auth', ['setQuote']),
    calculateChartWidth() {
      this.chartWidth = window.innerWidth - (this.primaryDrawerOn ? 82 : 320)
    },
    getQuoteData() {
      this.isLoading = true
      axios
        .get(`api/quote/${this.ticker}`)
        .then((response) => response.data)
        .then((data) => {
          this.quote = data
        })
        .catch(
          //  commit(types.SET_ERROR)
        ).finally(() => {
          this.isLoading = false
        }
        )
    }
  },
  watch: {
    primaryDrawerOn() {
      this.calculateChartWidth()
    },
    ticker() {
      this.getQuoteData()
    }
  },
  created() {
    this.setQuote(this.$route.params.ticker)
    this.getQuoteData()
  },
  mounted() {
    window.addEventListener('resize', this.calculateChartWidth())
    this.calculateChartWidth()
  }

}
</script>
