<template>
  <div class="mt-2">
    <filter-preset-item
      v-for="(preset, index) in items"
      :key="index"
      :is-active="itemIsActivePreset(preset)"
      :preset="preset"
    >
    </filter-preset-item>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import FilterPresetItem from '@/components/screener/FilterPresetItem'

export default {
  name: 'FilterPresets',
  components: {
    FilterPresetItem
  },
  computed: {
    items() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    ...mapState('screener', ['sorting']),
    ...mapState('screener_filters', ['totalItems']),
    ...mapFields('screener', ['activeFiltersPreset']),
    ...mapGetters('screener_filters', ['list', 'find']),
    ...mapFields('screener_filters', ['isLoading']),
    ...mapFields('auth', { activeFilterPresetId: 'profile.activeFilterPresetId' })
  },
  methods: {
    setActivePresetFromLocalStorage() {
      if (this.activeFilterPresetId) {
        const activePresetItem = this.find(this.activeFilterPresetId)

        if (activePresetItem) {
          this.activeFiltersPreset = activePresetItem
        }
      }
    },
    itemIsActivePreset(item) {
      return !!(this.activeFiltersPreset && item['@id'] === this.activeFiltersPreset['@id'])
    },

    getAllPresets() {
      this.resetList = true
      this.fetchAll()
    },
    ...mapActions('screener_filters', {
      fetchAll: 'fetchAll'
    })
  },
  watch: {
    totalItems(totalItems) {
      if (totalItems > 0) {
        this.setActivePresetFromLocalStorage()
      }
    }
  },
  created() {
    this.resetList = true
    this.fetchAll()

  }
}
</script>

<style scoped>

</style>
