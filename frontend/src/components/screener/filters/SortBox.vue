<template>
  <v-menu
    v-model="menu"
    :close-on-content-click="false"
    bottom
    origin="top left"
    right
    offset-y
    rounded="b"
    content-class="elevation-1 rounded-tl-0"
  >
    <template v-slot:activator="{ on }">
      <v-chip
        :class="{ 'ma-0 mr-1 mb-1 border': true, 'open': menu}"
        pill
        v-on="on"
      >

        <span class="font-weight-bold text--disabled" >{{ $t(`filters.sort.label`) }}</span>

        <span v-if="sort1Field" class="v-label font-weight-bold ml-2" v-html="$t(`filters.${sort1Field}.label`)"></span>
        <v-icon v-if="sort1Field && sort1Order === 'asc'" right color="green" small>mdi-triangle</v-icon>
        <v-icon
          v-if="sort1Field && sort1Order === 'desc'"
          right
          color="red"
          small
          class="icon-sort-desc"
        >mdi-triangle</v-icon>

<!--        <span v-if="sort2Field" class="v-label font-weight-bold ml-3" v-html="$t(`filters.${sort2Field}.label`)"></span>-->
<!--        <v-icon v-if="sort2Field && sort2Order === 'asc'" right color="green" small>mdi-triangle-outline</v-icon>-->
<!--        <v-icon-->
<!--          v-if="sort2Field && sort2Order === 'desc'"-->
<!--          right-->
<!--          color="red"-->
<!--          small-->
<!--          class="icon-sort-desc"-->
<!--        >mdi-triangle-outline</v-icon>-->

      </v-chip>
    </template>
    <v-card :width="340">
      <v-container fluid>
        <v-row>
          <v-col cols="8" class="pr-0">
            <v-autocomplete
              v-model="sort1Field"
              :items="choices"
              :label="$t('filters.sort.sortOne')"
              dense
              outlined
              hide-details
            ></v-autocomplete>
          </v-col>
          <v-col cols="4" class="pl-1" >
            <v-btn-toggle
              v-model="sort1Order"
              dense
              group
              mandatory
              tile
            >
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn v-bind="attrs" value="desc" v-on="on">
                    <v-icon color="red" class="icon-sort-desc">mdi-triangle</v-icon>
                  </v-btn>
                </template>
                <span>{{ $t('filters.sort.desc') }}</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn v-bind="attrs" value="asc" v-on="on">
                    <v-icon color="green">mdi-triangle</v-icon>
                  </v-btn>
                </template>
                <span>{{ $t('filters.sort.asc') }}</span>
              </v-tooltip>

            </v-btn-toggle>
          </v-col>
        </v-row>
        <v-row>
<!--          <v-col cols="8" class="pr-0">-->
<!--            <v-autocomplete-->
<!--              v-model="sort2Field"-->
<!--              :items="choices"-->
<!--              :label="$t('filters.sort.sortTwo')"-->
<!--              dense-->
<!--              outlined-->
<!--              hide-details-->
<!--              clearable-->
<!--            ></v-autocomplete>-->
<!--          </v-col>-->
<!--          <v-col cols="4" class="pl-1">-->
<!--            <v-btn-toggle-->
<!--              v-model="sort2Order"-->
<!--              dense-->
<!--              group-->
<!--              mandatory-->
<!--              tile-->
<!--            >-->
<!--              <v-tooltip bottom>-->
<!--                <template v-slot:activator="{ on, attrs }">-->
<!--                  <v-btn v-bind="attrs" value="desc" v-on="on">-->
<!--                    <v-icon color="red" class="icon-sort-desc" >mdi-triangle-outline</v-icon>-->
<!--                  </v-btn>-->
<!--                </template>-->
<!--                <span>{{ $t('filters.sort.desc') }}</span>-->
<!--              </v-tooltip>-->
<!--              <v-tooltip bottom>-->
<!--                <template v-slot:activator="{ on, attrs }">-->
<!--                  <v-btn v-bind="attrs" value="asc" v-on="on">-->
<!--                    <v-icon color="green">mdi-triangle-outline</v-icon>-->
<!--                  </v-btn>-->
<!--                </template>-->
<!--                <span>{{ $t('filters.sort.asc') }}</span>-->
<!--              </v-tooltip>-->

<!--            </v-btn-toggle>-->
<!--          </v-col>-->
        </v-row>
      </v-container>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn text @click="reset">reset</v-btn>
        <v-btn color="success" @click="menu = false" >OK</v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>

<script>
import filterSerialize from '../../../utils/filters/filterSerialize'
import { mapActions } from 'vuex'

export default {
  data() {
    return {
      menu: false,
      choices: [],
      label: 'd',
      valueOnOpenMenu: {
        sort1Field: null,
        sort2Field: null,
        sort1Order: null,
        sort2Order: null
      }

    }
  },
  computed: {
    sort1Field: {
      get () {
        return this.$store.state.screener.sorting.sort1.filterId ?
          this.$store.state.screener.sorting.sort1.filterId : 'chp'
      },
      set (value) {
        this.$store.commit('screener/SET_SORTING1', value)
      }
    },
    sort2Field: {
      get () {
        return this.$store.state.screener.sorting.sort2.filterId
      },
      set (value) {
        this.$store.commit('screener/SET_SORTING2', value)
      }
    },
    sort1Order: {
      get () {
        return this.$store.state.screener.sorting.sort1.order
      },
      set (value) {
        this.$store.commit('screener/SET_SORTING1_DIRECTION', value)
      }
    },
    sort2Order: {
      get () {
        return this.$store.state.screener.sorting.sort2.order
      },
      set (value) {
        this.$store.commit('screener/SET_SORTING2_DIRECTION', value)
      }
    },
    isEmpty() {
      return !(this.sort1Field || this.sort2Field)
    },
    isClose() {
      return !!(this.sort1Field || this.sort2Field)
    }
  },
  created() {
    filterSerialize.getSortableFilters().forEach((filterRow) => {
      this.choices.push({ text: this.$t(`filters.${filterRow.filterId}.label`), value: filterRow.filterId })
    })
  },
  methods: {
    ...mapActions('screener', ['search']),
    reset() {
      this.$store.commit('screener/RESET_SORTING')
      this.menu = false
    }
  },
  watch: {

    menu(value) {
      if (value) {
        this.valueOnOpenMenu.sort1Field = this.sort1Field
        this.valueOnOpenMenu.sort2Field = this.sort2Field
        this.valueOnOpenMenu.sort1Order = this.sort1Order
        this.valueOnOpenMenu.sort2Order = this.sort2Order
      }
      if (value === false && (
        this.valueOnOpenMenu.sort1Field !== this.sort1Field ||
        this.valueOnOpenMenu.sort2Field !== this.sort2Field ||
      this.valueOnOpenMenu.sort1Order !== this.sort1Order ||
      this.valueOnOpenMenu.sort2Order !== this.sort2Order
      )) this.search()
    }
  }
}
</script>

<style >
    .icon-sort-desc {
        transform: rotate(-180deg);
    }
</style>
