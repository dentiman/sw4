import moment from 'moment'
import { exchangeChoices } from './helper'
const prices = [1, 2, 3, 5, 7, 10, 15, 20, 30, 40, 50, 60, 70, 80, 90, 100, 150, 200]
const priceRangePresets = [
  { gte: '0.1', lte: null },
  { gte: '0.2', lte: null },
  { gte: '0.3', lte: null },
  { gte: '0.4', lte: null },
  { gte: '0.5', lte: null },
  { gte: '0.6', lte: null },
  { gte: '0.7', lte: null },
  { gte: '0.8', lte: null },
  { gte: '0.9', lte: null },
  { gte: '1', lte: null },
  { gte: '2', lte: null },
  { gte: '3', lte: null },
  { gte: '4', lte: null },
  { gte: '5', lte: null },
  { gte: null, lte: '0.1' },
  { gte: null, lte: '0.2' },
  { gte: null, lte: '0.3' },
  { gte: null, lte: '0.4' },
  { gte: null, lte: '0.5' },
  { gte: null, lte: '0.6' },
  { gte: null, lte: '0.7' },
  { gte: null, lte: '0.8' },
  { gte: null, lte: '0.9' },
  { gte: null, lte: '1' },
  { gte: null, lte: '2' },
  { gte: null, lte: '3' },
  { gte: null, lte: '4' },
  { gte: null, lte: '5' }
]
const priceChangePresets = [
  { gte: '0', lte: null },
  { gte: '0.1', lte: null },
  { gte: '0.2', lte: null },
  { gte: '0.3', lte: null },
  { gte: '0.4', lte: null },
  { gte: '0.5', lte: null },
  { gte: '0.6', lte: null },
  { gte: '0.7', lte: null },
  { gte: '0.8', lte: null },
  { gte: '0.9', lte: null },
  { gte: '1', lte: null },
  { gte: '1.5', lte: null },
  { gte: null, lte: '0' },
  { gte: null, lte: '-0.1' },
  { gte: null, lte: '-0.2' },
  { gte: null, lte: '-0.3' },
  { gte: null, lte: '-0.4' },
  { gte: null, lte: '-0.5' },
  { gte: null, lte: '-0.6' },
  { gte: null, lte: '-0.7' },
  { gte: null, lte: '-0.8' },
  { gte: null, lte: '-0.9' },
  { gte: null, lte: '-1' },
  { gte: null, lte: '-1.5' }
]
const percentChangePresets = [
  { gte: '0', lte: null },
  { gte: '1', lte: null },
  { gte: '2', lte: null },
  { gte: '3', lte: null },
  { gte: '4', lte: null },
  { gte: '5', lte: null },
  { gte: '6', lte: null },
  { gte: '7', lte: null },
  { gte: '8', lte: null },
  { gte: '9', lte: null },
  { gte: '10', lte: null },
  { gte: '20', lte: null },
  { gte: '30', lte: null },
  { gte: '40', lte: null },
  { gte: '50', lte: null },
  { gte: null, lte: '0' },
  { gte: null, lte: '-1' },
  { gte: null, lte: '-2' },
  { gte: null, lte: '-3' },
  { gte: null, lte: '-4' },
  { gte: null, lte: '-5' },
  { gte: null, lte: '-6' },
  { gte: null, lte: '-7' },
  { gte: null, lte: '-8' },
  { gte: null, lte: '-9' },
  { gte: null, lte: '-10' },
  { gte: null, lte: '-20' },
  { gte: null, lte: '-30' },
  { gte: null, lte: '-40' },
  { gte: null, lte: '-50' }
]

const humanizeVolume = function (value) {
  if (!value) return null
  const regex = /^([0-9]+)([K,M,B])$/
  const rezult = value.match(regex)

  if (rezult && rezult.length > 2) return value
  if (value * 1 >= 1000 && value * 1 < 1000000)  return `${value / 1000}K`
  if (value * 1 >= 1000000 && value * 1 < 1000000000)  return `${value / 1000000}M`
  if (value * 1 >= 1000000000 )  return `${value / 1000000000}B`
}

const unHumanizeVolume = function (value) {
  if (!value) return null
  const regex = /^([0-9]+)([K,M,B])$/
  const rezult = value.match(regex)

  if (!rezult || rezult.length < 2) return value
  if (rezult[2] === 'K') return  `${rezult[1]}000`
  if (rezult[2] === 'M') return `${rezult[1]}000000`
  if (rezult[2] === 'B') return `${rezult[1]}000000000`

  return value
}

const humanizeMillionVolume = function (value) {
  if (!value) return null
  const regex = /^([0-9]+)([K,M,B])$/
  const rezult = value.match(regex)

  if (rezult && rezult.length > 2) return value
  if (value * 1 >= 1000 && value * 1 < 1000000)  return `${value / 1000}K`
  if (value * 1 >= 1000000 && value * 1 < 1000000000)  return `${value / 1000000}M`
  if (value * 1 >= 1000000000 )  return `${value / 1000000000}B`
}

const unHumanizeMillionVolume = function (value) {
  if (!value) return null
  const regex = /^([0-9]+)([K,M,B])$/
  const rezult = value.match(regex)

  if (!rezult || rezult.length < 2) return `${value / 1000000}`
  if (rezult[2] === 'K') return  `${rezult[1] * 1 / 1000}`
  if (rezult[2] === 'M') return `${rezult[1]}`
  if (rezult[2] === 'B') return `${rezult[1]}000`

  return value
}

const humanizeValue = function (value) {
  return  value
}
const unHumanizeValue = function (value) {
  return  value
}

const unHumanizeDateValue = function (value) {

  if (Number.isInteger(value)) {
    return  moment().add(value, 'days').format('YYYY-MM-DD')
  }

  return value

}

const volume = [
  '10K',
  '20K',
  '50K',
  '100K',
  '150K',
  '200K',
  '300K',
  '400K',
  '500K',
  '700K',
  '1M',
  '2M',
  '3M',
  '4M',
  '5M',
  '10M',
  '20M'
]

const relativeVolumePresets = [
  { gte: '1.1', lte: null },
  { gte: '1.2', lte: null },
  { gte: '1.3', lte: null },
  { gte: '1.5', lte: null },
  { gte: '1.7', lte: null },
  { gte: '2', lte: null },
  { gte: '3', lte: null }
]

// const LevelPresets = [
//     {gte: '0', lte: '0', label: '*.00$'},
//     {gte: '0.5', lte: '0.5', label: '*.50$'},
// ]

const MarketCapChoices = [
  '50M',
  '100M',
  '500M',
  '1B',
  '2B',
  '5B',
  '10B',
  '50B',
  '100B',
  '200B',
  '500B'
]

export default {
  averageDailyVolume10Day: {
    type: 'range',
    displayType: 'volume',
    sortable: true,
    humanizeValue: humanizeVolume,
    unHumanizeValue: unHumanizeVolume,
    search: true,
    custom: true,
    group: 'basic',
    order: 106,
    choices: volume
  },
  averageDailyVolume3Month: {
    type: 'range',
    displayType: 'volume',
    sortable: true,
    humanizeValue: humanizeVolume,
    unHumanizeValue: unHumanizeVolume,
    search: true,
    custom: true,
    group: 'basic',
    order: 105,
    choices: volume
  },
  fiftyDayAverage: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 205,
    choices: []
  },
  fiftyDayAverageChange: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 206,
    choices: [],
    presets: priceRangePresets
  },
  fiftyDayAverageChangePercent: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 207,
    choices: [],
    presets: percentChangePresets
  },
  twoHundredDayAverage: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 208,
    choices: []
  },
  twoHundredDayAverageChange: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 209,
    choices: [],
    presets: priceRangePresets
  },
  twoHundredDayAverageChangePercent: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 210,
    choices: [],
    presets: percentChangePresets
  },
  fiftyTwoWeekHigh: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 211,
    choices: []
  },
  fiftyTwoWeekHighChange: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 212,
    choices: [],
    presets: priceRangePresets
  },
  fiftyTwoWeekHighChangePercent: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 213,
    choices: [],
    presets: percentChangePresets
  },
  fiftyTwoWeekLow: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 214,
    choices: []
  },
  fiftyTwoWeekLowChange: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 215,
    choices: [],
    presets: priceRangePresets
  },
  fiftyTwoWeekLowChangePercent: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 216,
    choices: [],
    presets: percentChangePresets
  },
  forwardPE: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'fundamental',
    order: 302,
    choices: [],
    presets: [
      { gte: null, lte: '5' },
      { gte: null, lte: '10' },
      { gte: null, lte: '15' },
      { gte: null, lte: '20' },
      { gte: null, lte: '25' },
      { gte: null, lte: '30' },
      { gte: null, lte: '35' },
      { gte: null, lte: '40' },
      { gte: null, lte: '45' },
      { gte: null, lte: '50' },
      { gte: null, lte: '100' },
      { gte: null, lte: '500' },
      { gte: '5', lte: null },
      { gte: '10', lte: null },
      { gte: '15', lte: null },
      { gte: '20', lte: null },
      { gte: '25', lte: null },
      { gte: '30', lte: null },
      { gte: '35', lte: null },
      { gte: '40', lte: null },
      { gte: '45', lte: null },
      { gte: '50', lte: null },
      { gte: '100', lte: null },
      { gte: '500', lte: null }
    ]
  },
  marketCap: {
    type: 'range',
    displayType: 'millionVolume',
    sortable: true,
    humanizeValue: humanizeMillionVolume,
    unHumanizeValue: unHumanizeMillionVolume,
    search: true,
    custom: true,
    group: 'basic',
    order: 110,
    choices: MarketCapChoices
  },
  sharesOutstanding: {
    type: 'range',
    displayType: 'millionVolume',
    sortable: true,
    humanizeValue: humanizeMillionVolume,
    unHumanizeValue: unHumanizeMillionVolume,
    search: true,
    custom: true,
    group: 'fundamental',
    order: 301,
    choices: [
      '100K',
      '500K',
      '1M',
      '2M',
      '5M',
      '10M',
      '50M',
      '100M',
      '200M',
      '300M',
      '500M',
      '1B',
      '2B',
      '5B'
    ]
  },

  index: {
    type: 'range',
    displayType: 'text',
    sortable: false,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 107,
    choices: [],
    presets:  [
      { gte: '1', lte: null, label: 'filters.choices.index.sp500' },
      { gte: '2', lte: null, label: 'filters.choices.index.djia' },
      { gte: null, lte: '0', label: 'filters.choices.index.no_sp500' }
    ]
  },
  atr: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 107,
    choices: [],
    presets: priceRangePresets

  },
  ind: {
    humanizeValue,
    unHumanizeValue,
    type: 'choice',
    displayType: 'text',
    sortable: true,
    group: 'basic',
    order: 113,
    choices: [
      { value: 'Exchange Traded Fund', text: 'filters.choices.ind.etf' }
    ]
  },
  etf: {
    humanizeValue,
    unHumanizeValue,
    type: 'choice',
    displayType: 'text',
    sortable: true,
    group: 'basic',
    order: 120,
    choices: [
      { value: '1', text: 'filters.choices.etf.1' },
      { value: '0', text: 'filters.choices.etf.0' }
    ]
  },
  country: {
    humanizeValue,
    unHumanizeValue,
    type: 'choice',
    displayType: 'text',
    sortable: true,
    group: 'basic',
    order: 112,
    choices: [
      { value: 'USA', text: 'USA' }
    ]
  },
  sector: {
    humanizeValue,
    unHumanizeValue,
    type: 'multichoice',
    displayType: 'sector',
    sortable: true,
    group: 'basic',
    order: 111,
    choices: [
      { value: 'Basic Materials', text: 'Basic Materials' },
      { value: 'Conglomerates', text: 'Conglomerates' },
      { value: 'Consumer Goods', text: 'Consumer Goods' },
      { value: 'Financial', text: 'Financial' },
      { value: 'Healthcare', text: 'Healthcare' },
      { value: 'Industrial Goods', text: 'Industrial Goods' },
      { value: 'Services', text: 'Services' },
      { value: 'Technology', text: 'Technology' },
      { value: 'Utilities', text: 'Utilities' }
    ]
  },

  // #  Level1
  price: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 101,
    choices: prices
  },
  op: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'level1',
    order: 400,
    choices: prices
  },
  hi: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'level1',
    order: 400,
    choices: prices
  },
  lo: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'level1',
    order: 400,
    choices: prices
  },
  ttime: {
    type: null, // 'date',
    displayType: 'datetime',
    sortable: false,
    humanizeValue,
    unHumanizeValue,
    search: false,
    custom: true,
    group: 'level1',
    order: 400,
    choices: prices
  },
  chp: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 102,
    choices: [],
    presets: percentChangePresets
  },
  ch: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 103,
    choices: [],
    presets: priceChangePresets
  },
  bid: {
    type: null, // 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: false,
    custom: true,
    group: 'level1',
    order: 400,
    choices: []
  },
  ask: {
    type: null, //'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: false,
    custom: true,
    group: 'level1',
    order: 400,
    choices: []
  },
  spread: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'level1',
    order: 401,
    choices: [],
    presets: [
      { gte: '0.01', lte: null },
      { gte: '0.02', lte: null },
      { gte: '0.03', lte: null },
      { gte: '0.04', lte: null },
      { gte: '0.05', lte: null },
      { gte: '0.06', lte: null },
      { gte: '0.07', lte: null },
      { gte: '0.08', lte: null },
      { gte: '0.1', lte: null },
      { gte: null, lte: '0.01' },
      { gte: null, lte: '0.02' },
      { gte: null, lte: '0.03' },
      { gte: null, lte: '0.04' },
      { gte: null, lte: '0.05' },
      { gte: null, lte: '0.06' },
      { gte: null, lte: '0.07' },
      { gte: null, lte: '0.08' },
      { gte: null, lte: '0.09' },
      { gte: null, lte: '0.1' }
    ]
  },
  bidsize: {
    type: null, //'range',
    displayType: 'text',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'level1',
    order: 400,
    choices: []
  },
  asksize: {
    type: null, //'range',
    displayType: 'text',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: false,
    custom: true,
    group: 'level1',
    order: 400,
    choices: []
  },
  tcount: {
    type: 'range',
    displayType: 'text',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: false,
    custom: true,
    group: 'level1',
    order: 400,
    choices: []
  },
  vol: {
    type: 'range',
    displayType: 'volume',
    sortable: true,
    humanizeValue: humanizeVolume,
    unHumanizeValue: unHumanizeVolume,
    search: true,
    custom: true,
    group: 'basic',
    order: 104,
    choices: volume
  },

  // #  Premarket
  pvol: {
    type: 'range',
    displayType: 'volume',
    sortable: true,
    humanizeValue: humanizeVolume,
    unHumanizeValue: unHumanizeVolume,
    search: true,
    custom: true,
    group: 'premarket',
    order: 504,
    choices: volume
  },
  ptcount: {
    type: 'range',
    displayType: 'text',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'premarket',
    order: 505,
    choices: []
  },
  pprice: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'premarket',
    order: 501,
    choices: prices
  },
  pchp: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'premarket',
    order: 502,
    choices: [],
    presets: percentChangePresets
  },
  pch: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'premarket',
    order: 503,
    choices: [],
    presets: priceChangePresets
  },

  // CalculateTrrait--------------------------
  relativeVolume: {
    type: 'range',
    displayType: 'text',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 108,
    choices: [],
    presets: relativeVolumePresets
  },
  gep: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'basic',
    order: 114,
    choices: [],
    presets: percentChangePresets
  },
  priceRange: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 201,
    choices: [],
    presets: priceRangePresets
  },
  chpo: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 203,
    choices: [],
    presets: percentChangePresets
  },
  cho: {
    type: 'range',
    displayType: 'net',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 202,
    choices: [],
    presets: priceChangePresets
  },
  atrp: {
    type: 'range',
    displayType: 'percent',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'tech',
    order: 204,
    choices: [],
    presets: percentChangePresets
  },
  newHigh: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'signal',
    order: 600,
    choices: [],
    presets:  [
      { gte: null, lte: '0', label: 'filters.choices.newHigh.new-high' },
      { gte: null, lte: '0.01', label: 'filters.choices.newHigh.below-high-01' },
      { gte: null, lte: '0.02', label: 'filters.choices.newHigh.below-high-02' },
      { gte: null, lte: '0.03', label: 'filters.choices.newHigh.below-high-03' },
      { gte: null, lte: '0.04', label: 'filters.choices.newHigh.below-high-04' },
      { gte: null, lte: '0.05', label: 'filters.choices.newHigh.below-high-05' }
    ]
  },
  newLow: {
    type: 'range',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'signal',
    order: 600,
    choices: [],
    presets:  [
      { gte: null, lte: '0', label: 'filters.choices.newLow.new-low' },
      { gte: null, lte: '0.01', label: 'filters.choices.newLow.above-low-01' },
      { gte: null, lte: '0.02', label: 'filters.choices.newLow.above-low-02' },
      { gte: null, lte: '0.03', label: 'filters.choices.newLow.above-low-03' },
      { gte: null, lte: '0.04', label: 'filters.choices.newLow.above-low-04' },
      { gte: null, lte: '0.05', label: 'filters.choices.newLow.above-low-05' }
    ]
  },
  level: {
    type: 'level',
    displayType: 'price',
    sortable: true,
    humanizeValue,
    unHumanizeValue,
    search: true,
    custom: true,
    group: 'signal',
    order: 600,
    choices: []
  },
  earntime: {
    humanizeValue,
    unHumanizeValue,
    type: 'multichoice',
    displayType: 'text',
    sortable: true,
    group: 'basic',
    order: 140,
    choices: [
      { text: 'BMO',value: 'bmo' },
      { text: 'AMC',value: 'amc' }
    ]
  },
  earn: {
    humanizeValue,
    unHumanizeValue: unHumanizeDateValue,
    type: 'date',
    displayType: 'date',
    sortable: true,
    group: 'basic',
    order: 130,
    presets: [
      [-1,-1],
      [0,0],
      [0,5]
    ]
  },
  ipo: {
    humanizeValue,
    unHumanizeValue,
    type: 'date',
    displayType: 'date',
    sortable: true,
    group: 'other',
    order: 700
  },
  exchange: {
    humanizeValue,
    unHumanizeValue,
    type: 'multichoice',
    displayType: 'exchange',
    sortable: true,
    group: 'basic',
    order: 109,
    choices: exchangeChoices()
  },
  // sort: {
  //     humanizeValue,
  //     unHumanizeValue,
  //     type: null,
  //     sortable: false,
  //     group: 'sort',
  //     order: 0
  // },
  id: {
    humanizeValue,
    unHumanizeValue,
    displayType: 'ticker',
    type: 'ticker',
    sortable: true,
    group: 'other',
    order: 0
  }
}
