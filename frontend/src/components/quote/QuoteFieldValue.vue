<template>
  <span>
    <router-link
      v-if="fieldId === 'id' && preventClick === true"
      :to="`/quote/${fieldValue}`"
      :style="elStyle"
      :class="cssClass"
    >{{ displayValue }}</router-link>
    <span v-else :class="cssClass" :style="elStyle" >{{ displayValue }}</span>
  </span>
</template>

<script>
import formData from '@/utils/filters/formData'
import { exchanges,exchangeColors } from '@/utils/filters/helper'
import moment from 'moment'
export default {
  name: 'QuoteFieldValue',
  props: {
    preventClick: {
      default: true
    },
    fieldId: {
      require: true
    },
    elStyle: {
      type: String,
      default: ''
    },
    elClass: {
      type: String,
      default: ''
    },
    fieldValue: {
      require: true
    },
    exchange: {
      require: true
    },
    externalClass: {
      require: false
    }
  },
  computed: {
    cssClass() {
      const array = [`qf-${this.displayType}` , this.elClass]

      if (this.displayType === 'percent' || this.displayType === 'net') {
        array.push(this.colorChange(this.fieldValue))
      }
      if (this.displayType === 'exchange' || this.fieldId === 'id' ) {
        array.push(`${this.exchangeColor}--text text--contrast-3 font-weight-medium`)
      }
      if (this.displayType === 'volume' || this.displayType === 'millionVolume' ) {
        array.push('blue_c3--text  font-weight-medium')
      }
      if ( this.externalClass) {
        array.push(this.externalClass)
      }

      return array
    },
    displayType() {
      if (formData[this.fieldId])  return formData[this.fieldId].displayType

      return  'not-found'
    },
    exchangeColor() {
      return exchangeColors[this.exchange]
    },
    displayValue() {
      switch (this.displayType) {
      case 'volume':
        return this.volumeValue(this.fieldValue)
      case 'millionVolume':
        return this.millionValue(this.fieldValue)
      case 'net':
        return this.netValue(this.fieldValue)
      case 'percent':
        return this.percentValue(this.fieldValue)
      case 'price':
        return this.priceValue(this.fieldValue)
      case 'text':
        return this.textValue(this.fieldValue)
      case 'sector':
        return this.sectorValue(this.fieldValue)
      case 'date':
        return this.dateValue(this.fieldValue)
      case 'datetime':
        return this.dateTimeValue(this.fieldValue)
      case 'exchange':
        return this.exchangeValue(this.fieldValue)
      case 'not-found':
        return this.fieldId
      default:
        return this.fieldValue
      }
    }
  },
  methods: {
    handleTickerClick(ticker) {
      this.$router.push({ name: 'Quote', params: { ticker: ticker } })
    },
    priceValue(value) {
      if (!value) return null
      if (typeof value !== 'number') return  value

      return `${value.toFixed(2)}$`
    },
    netValue(value) {
      if (!value) return null
      if (typeof value !== 'number') return  value

      return value.toFixed(2)
    },
    percentValue(value) {
      if (!value) return null
      if (typeof value !== 'number') return  value

      return `${value.toFixed(2)}%`
    },
    textValue(value) {
      return value
    },
    dateValue(value) {
      if (!value) return null

      return  moment(value).format('DD/MM/YYYY')
    },
    dateTimeValue(value) {
      if (!value) return null

      return value.substring(0, 16).replace('T',' ')
    },
    sectorValue(value) {
      return value
    },
    exchangeValue(value) {
      if (!value || !exchanges[value]) return  '-'

      return exchanges[value]
    },
    tickerValue(value) {
      return value
    },
    volumeValue(value) {
      if (!value) return  '-'
      if (typeof value !== 'number') return  value
      if (value  >= 1000 && value  < 1000000)  return `${(value / 1000).toFixed(0)}K`
      if (value >= 1000000 && value  < 1000000000)  return `${(value / 1000000).toFixed(2)}M`
      if (value  >= 1000000000 )  return `${(value / 1000000000).toFixed(2)}B`
    },
    millionValue(value) {
      if (!value) return  '-'
      if (typeof value !== 'number') return  value
      if (value   < 1000)  return `${(value).toFixed(0)}M`
      if (value >= 1000)  return `${(value / 1000).toFixed(2)}B`

    },
    colorChange(value) {
      if (!value) return false
      if (typeof value !== 'number' || value === 0) return  false
      const direction = value > 0 ? 'green_c2' : 'red_c2'

      // let lighten = this.$vuetify.theme.dark ? 'lighten' : 'darken'
      return `${direction}--text  font-weight-medium`
    }
  }
}
</script>

<style scoped>
   .qf-price {
        font-weight: 500;
    }
</style>
