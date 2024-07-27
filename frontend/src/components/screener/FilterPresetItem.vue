<template>
  <v-chip
    :close="!isLoading"
    :close-icon="activePresetCloseIcon"
    :color="presetButtonColor"
    :disabled="isLoading"
    class="mr-2 text-uppercase font-weight-black grey--text text--darken-4"
    label
    loading
    @click="activatePreset"
    @click:close="onActivePresetClose()"
  >
    {{ preset.name }}
    <v-progress-circular
      v-if="isLoading"
      class="ml-2"
      color="primary"
      indeterminate
      size="20"
    ></v-progress-circular>
  </v-chip>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'

//item = { name:'ff', query: 'ff'}
export default {
  name: 'FilterPresetItem',
  props: {
    preset: {
      type: Object,
      required: true
    },
    isActive: {
      required: true
    }
  },

  data() {
    return {
      isLoading: false
    }
  },
  computed: {
    ...mapGetters('screener', ['selectedFilters']),
    ...mapState('screener', ['sorting']),
    ...mapFields('screener', ['activeFiltersPreset']),
    ...mapGetters('screener_filters', ['list']),
    ...mapFields('screener_filters', ['deleted', 'error', 'created', 'updated', 'resetList', 'totalItems', 'view']),
    ...mapFields('auth', { activeFilterPresetId: 'profile.activeFilterPresetId' }),
    presetButtonColor() {
      if (this.isActive) {
        if (this.isActivePresetNotChanged && this.isLoading === false) return 'blue_a1'

        return 'blue_o1'
      }

      return null
    },
    activePresetCloseIcon() {
      if (!this.isActive) return 'mdi-close'
      if (this.isActivePresetNotChanged) {
        return 'mdi-close'
      }

      return 'mdi-content-save-edit'
    },
    isActivePresetNotChanged() {
      if (this.activeFiltersPreset == null) {
        return false
      }

      return this.activeFiltersPreset.filters === JSON.stringify(this.selectedFilters) &&
          this.activeFiltersPreset.sorting === JSON.stringify(this.sorting)
    }
  },
  methods: {
    onActivePresetClose() {
      if (!this.isActive) {
        this.removeFilterPreset()

        return
      }
      if (this.isActivePresetNotChanged) {
        this.removeFilterPreset()

        return
      }
      this.saveActivePreset()
    },

    activatePreset() {
      this.activeFilterPresetId =  this.preset['@id']
      this.activeFiltersPreset = this.preset
    },
    isCloseIcon() {
      if (this.isLoading) return false

      return true
    },

    saveActivePreset() {
      this.isLoading = true
      this.activeFiltersPreset = {
        ...this.activeFiltersPreset,
        ...{
          filters: JSON.stringify(this.selectedFilters),
          sorting: JSON.stringify(this.sorting)
        }
      }

      this.updateItem(this.activeFiltersPreset).then(() => {
        this.isLoading = false
      })
    },
    removeFilterPreset() {
      this.$confirm('Do you really want to delete this item?',{ color: 'error', title: 'Confirmation', icon: 'mdi-alert' }).then((res) => {
        if (res) {
          this.isLoading = true
          this.deleteItem(this.preset).then(() => {
            if (this.isActive) {
              this.activeFilterPresetId = null
              this.activeFiltersPreset = null
              this.isLoading = false
            }
            // this.getAllPresets();
          })
        }
      })
    },
    setActivePreset() {
      this.activeFiltersPreset = this.preset
    },

    ...mapActions('screener_filters', {
      deleteItem: 'del',
      updateItem: 'update'
    })
  }
}
</script>

<style scoped>

</style>
