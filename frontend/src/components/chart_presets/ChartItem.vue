<template>
  <div>
    <v-tabs
      v-model="tabs"
      centered
      grow
      hide-slider
      show-arrows
      :color="color"
    >
      <v-tab>
        {{ $t('chart.chartArea') }}
      </v-tab>
      <v-tab>
        {{ $t('chart.bars') }}
      </v-tab>
      <v-tab>
        {{ $t('chart.ma') }}
      </v-tab>
      <v-tab>
        {{ $t('chart.lines') }}
      </v-tab>

    </v-tabs>
    <v-tabs-items v-model="tabs" class="px-2">
      <v-tab-item  :reverse-transition="false" :transition="false">
        <v-row justify="center" class="mt-0">
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="width"
              hide-details
              :items="widthItems"
              :label=" $t('chart.width') "
            ></v-select>
          </v-col>
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="height"
              hide-details
              :items="heightItems"
              :label=" $t('chart.height') "
            ></v-select>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0">
          <v-col class="pb-1" cols="6" >
            <v-select
              :color="color"
              dense
              v-model="volumeAreaHeight"
              :items="volumeAreaHeightItems"
              :label="$t('chart.volumeAreaHeight')"
            ></v-select>
          </v-col>
          <v-col class="pb-1" cols="6" >
            <v-switch
              :color="color"
              v-model="separateVolumeArea"
              :label="$t('chart.separateVolumeArea')"
              class="mx-2"
            ></v-switch>
          </v-col>
        </v-row>

        <v-row justify="center" class="mt-0">
          <v-col class="pb-1 pr-1 pt-0" cols="6" >
            <v-list-item two-line class="px-0">
              <v-list-item-avatar>
                <color-picker :patch="`activeChartPreset.${this.mappedChart}.bgColor`" ></color-picker>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{ $t('chart.bgColor') }}</v-list-item-title>
                <v-list-item-subtitle>{{ $t('chart.bgColor_d') }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-col>
          <v-col class="pb-1 pt-0" cols="6" >
            <v-list-item two-line class="px-0">
              <v-list-item-avatar>
                <color-picker :patch="`activeChartPreset.${this.mappedChart}.gridColor`" ></color-picker>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{ $t('chart.gridColor') }}</v-list-item-title>
                <v-list-item-subtitle>{{ $t('color') }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-col>
        </v-row>

        <v-row justify="center" class="mt-0">
          <v-col class="pb-1 pt-1"  cols="6"  >
            <v-switch
              :color="color"
              v-model="spyOn"
              :label="$t('chart.spyOn')"
              class="mt-2"
            ></v-switch>
          </v-col>
          <v-col class="pb-1 pt-1"  cols="6" >
            <v-list-item two-line class="px-0">
              <v-list-item-avatar>
                <color-picker :patch="`activeChartPreset.${this.mappedChart}.spyColor`" ></color-picker>
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>{{ $t('chart.spyColor') }}</v-list-item-title>
                <v-list-item-subtitle>{{ $t('chart.lineColor') }}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-col>
        </v-row>

        <v-row  class="mt-0">
          <v-col class="pt-1 " cols="12">
            <v-switch
              :color="color"
              v-model="preMarket"
              class="mt-0"
              :label="$t('chart.preMarket')"
            ></v-switch>
          </v-col>
        </v-row>
      </v-tab-item>

      <v-tab-item :reverse-transition="false" :transition="false">
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="barType"
              hide-details
              :items="batTypeItems"
              :label=" $t('chart.barType') "
            ></v-select>
          </v-col>
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="barWidth"
              hide-details
              :items="barWidthItems"
              :label=" $t('chart.barWidth') "
            ></v-select>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="barThick"
              hide-details
              :items="barThickItems"
              :label="$t('chart.barThick')"
            ></v-select>
          </v-col>
          <v-col >
            <v-select
              :color="color"
              v-model="outline"
              dense
              hide-details
              :items="outlineItems"
              :label="$t('chart.outline')"
            ></v-select>
          </v-col>
        </v-row>
        <v-row >
          <v-col cols="6" class="pr-0" >
            <v-list>
              <v-list-item two-line class="px-0">
                <v-list-item-avatar>
                  <color-picker :patch="`activeChartPreset.${this.mappedChart}.colorUp`" ></color-picker>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ $t('chart.candleBody') }}</v-list-item-title>
                  <v-list-item-subtitle>{{ $t('chart.upColor') }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item two-line class="px-0">
                <v-list-item-avatar>
                  <color-picker :patch="`activeChartPreset.${this.mappedChart}.outlineColorUp`" ></color-picker>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ $t('chart.barCandlewick') }}</v-list-item-title>
                  <v-list-item-subtitle>{{ $t('chart.upColor') }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item two-line class="px-0">
                <v-list-item-avatar>
                  <color-picker :patch="`activeChartPreset.${this.mappedChart}.volumeColorUp`" ></color-picker>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ $t('chart.volumeColor') }}</v-list-item-title>
                  <v-list-item-subtitle>{{ $t('chart.upColor') }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>

            </v-list>
          </v-col>
          <v-col cols="6" >
            <v-list>
              <v-list-item two-line class="px-0">
                <v-list-item-avatar>
                  <color-picker :patch="`activeChartPreset.${this.mappedChart}.colorDown`" ></color-picker>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ $t('chart.candleBody') }}</v-list-item-title>
                  <v-list-item-subtitle>{{ $t('chart.downColor') }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item two-line class="px-0">
                <v-list-item-avatar>
                  <color-picker :patch="`activeChartPreset.${this.mappedChart}.outlineColorDown`" ></color-picker>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ $t('chart.barCandlewick') }}</v-list-item-title>
                  <v-list-item-subtitle>{{ $t('chart.downColor') }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item two-line class="px-0">
                <v-list-item-avatar>
                  <color-picker :patch="`activeChartPreset.${this.mappedChart}.volumeColorDown`" ></color-picker>
                </v-list-item-avatar>
                <v-list-item-content>
                  <v-list-item-title>{{ $t('chart.volumeColor') }}</v-list-item-title>
                  <v-list-item-subtitle>{{ $t('chart.downColor') }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-col>
        </v-row>
      </v-tab-item>

      <v-tab-item :reverse-transition="false" :transition="false">
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="sma1"
              clearable
              class="mt-0 pt-0"
              hide-details
              :items="maItems"
              :label="$t('chart.sma1')"
            ></v-select>
          </v-col>
          <v-col class="pt-4">
            <color-picker :patch="`activeChartPreset.${this.mappedChart}.sma1Color`" ></color-picker>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              v-model="sma2"
              dense
              clearable
              class="mt-0 pt-0"
              hide-details
              :items="maItems"
              :label="$t('chart.sma2')"
            ></v-select>
          </v-col>
          <v-col class="pt-4">
            <color-picker :patch="`activeChartPreset.${this.mappedChart}.sma2Color`" ></color-picker>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              v-model="sma3"
              dense
              clearable
              class="mt-0 pt-0"
              hide-details
              :items="maItems"
              :label="$t('chart.sma3')"
            ></v-select>
          </v-col>
          <v-col class="pt-4">
            <color-picker :patch="`activeChartPreset.${this.mappedChart}.sma3Color`" ></color-picker>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              dense
              v-model="ema1"
              clearable
              class="mt-0 pt-0"
              hide-details
              :items="maItems"
              :label="$t('chart.ema1')"
            ></v-select>
          </v-col>
          <v-col class="pt-4">
            <color-picker :patch="`activeChartPreset.${this.mappedChart}.ema1Color`" ></color-picker>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0" >
          <v-col >
            <v-select
              :color="color"
              v-model="ema2"
              dense
              clearable
              class="mt-0 pt-0"
              hide-details
              :items="maItems"
              :label="$t('chart.ema2')"
            ></v-select>
          </v-col>
          <v-col class="pt-4">
            <color-picker :patch="`activeChartPreset.${this.mappedChart}.ema2Color`" ></color-picker>
          </v-col>
        </v-row>
        <v-row justify="center" class="mt-0 mb-1" >
          <v-col >
            <v-select
              :color="color"
              v-model="ema3"
              dense
              clearable
              class="mt-0 pt-0"
              hide-details
              :items="maItems"
              :label="$t('chart.ema3')"
            ></v-select>
          </v-col>
          <v-col class="pt-4">
            <color-picker :patch="`activeChartPreset.${this.mappedChart}.ema3Color`" ></color-picker>
          </v-col>
        </v-row>
      </v-tab-item>

      <v-tab-item reverse-transition="fade-transition" transition="fade-transition">
<!--        //TODO: fix calculation in backend-->
<!--        <v-row justify="center" class="mt-2" >-->
<!--          <v-col>-->
<!--            <v-switch-->
<!--              v-model="linesOpen"-->
<!--              :label="$t('chart.linesOpen')"-->
<!--              class="mt-0 pt-0"-->
<!--            ></v-switch>-->
<!--          </v-col>-->
<!--          <v-col >-->
<!--            <v-switch-->
<!--              v-model="linesHigh"-->
<!--              :label="$t('chart.linesHigh')"-->
<!--              class="mt-0 pt-0"-->
<!--            ></v-switch>-->
<!--          </v-col>-->
<!--        </v-row>-->
<!--        <v-row justify="center" class="mt-0">-->
<!--          <v-col >-->
<!--            <v-switch-->
<!--              v-model="linesLow"-->
<!--              :label="$t('chart.linesLow')"-->
<!--              class="mt-0 pt-0"-->
<!--            ></v-switch>-->
<!--          </v-col>-->
<!--          <v-col >-->
<!--            <v-switch-->
<!--              v-model="linesClose"-->
<!--              :label="$t('chart.linesClose')"-->
<!--              class="mt-0 pt-0"-->
<!--            ></v-switch>-->
<!--          </v-col>-->
<!--        </v-row>-->

        <v-row justify="center" class="mt-0">
          <v-col >
            <v-switch
              :color="color"
              v-model="lineLastPrice"
              :label="$t('chart.lineLastPrice')"
              class="mt-0 pt-0"
            ></v-switch>
          </v-col>
          <v-col class="pt-1" >
<!--            <v-select-->
<!--              v-model="linesDays"-->
<!--              :items="linesDaysItems"-->
<!--              :label="$t('chart.linesDays')"-->
<!--            ></v-select>-->
          </v-col>
        </v-row>
      </v-tab-item>
    </v-tabs-items>
  </div>
</template>

<script>
import ColorPicker from '@/components/ColorPicker'
import { chartLayout } from '@/utils/chartLayout'

const mapChartItemProperties = () => {
  const fields = chartLayout()

  return Object.keys(fields).reduce((prev, key) => {
    const field = {
      get() {
        return this.$store.getters['screener/getField'](`activeChartPreset.${this.mappedChart}.${key}`)
      },
      set(value) {
        this.$store.commit('screener/updateField', {
          path: `activeChartPreset.${this.mappedChart}.${key}`,
          value: value
        })
      }
    } // eslint-disable-next-line no-param-reassign

    prev[key] = field

    return prev
  }, {})
}

export default {
  name: 'ChartItem',
  components: {
    ColorPicker
  },
  props: {
    color: {
      require: false,
      type: String,
      default: 'primary'
    },
    mappedChart: {
      require: true
    }
  },
  computed: {
    ...mapChartItemProperties()
  },
  data() {
    return {
      chart: null,
      linesDaysItems: [1, 2, 3, 4],
      barWidthItems: [1, 2, 3, 4, 5, 6, 7],
      barThickItems: [1, 2, 3, 4, 5, 6, 7],
      outlineItems: [1, 2, 3, 4, 5, 6, 7],
      volumeBarWidthItems: [1, 2, 3, 4, 5, 6, 7],
      maItems: [10, 20, 50, 100, 200],
      batTypeItems: [
        { text: this.$t('chart.barTypeCandles'), value: 'candle' },
        { text: this.$t('chart.barTypeBars'), value: 'bar' }
      ],
      widthItems: [400,500,600,700,800,900,1000,1200,1500,1600,1800],
      heightItems:  [200,300,400,500,600,700,800,900,1000],
      volumeAreaHeightItems: [30,40,50,60,70],
      tabs: null
    }
  },
  watch: {
    chart: {
      handler(chart) {
        this.item = chart
      },
      deep: true
    }
  },
  created() {
    this.chart = { ...this.item }
  }
}
</script>

<style scoped>
.theme--dark.v-tabs > .v-tabs-bar,
.theme--dark.v-tabs-items {
  background-color: transparent;
}

</style>
