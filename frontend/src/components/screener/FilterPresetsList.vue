<template>
  <span>
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      bottom
      :max-width="menuWidth"
      right
      offset-y
      rounded="b"
      content-class="elevation-1 rounded-tl-0"
    >
      <template v-slot:activator="{ on }">
        <v-chip
          :loading="isLoading"
          :class="{ 'ma-0 mr-1 mb-1 border': true, 'open': menu}"
          v-on="on"
        >
          <v-icon :color="activeFilterPresetId ? (isActivePresetNotChanged ? 'primary' : 'warning') : ''" class="mr-1">mdi-account</v-icon>
          <span
            class="font-weight-bold  text--primary">
            {{ label }}
          </span>
          <v-icon >mdi-menu-down</v-icon>
        </v-chip>
      </template>
      <v-card
        class="d-flex flex-column flex-md-row"
        :width="menuWidth"
        rounded="0"
        outlined
        elevation="2"
      >
        <div class="flex-grow-1 pa-0">
          <v-list dense max-width="220">
            <v-list-item-group
              :value="activeFilterPresetId"
            >
              <v-list-item
                v-for="preset in items"
                :key="preset['@id']"
                :value="preset['@id']"
                @click="onPresetClick(preset)"
              >
                <v-list-item-icon>
                  <v-icon :color="itemIsActivePreset(preset) ? 'success' : ''">mdi-circle-medium</v-icon>
                </v-list-item-icon>
                <v-list-item-title >{{ preset.name }}</v-list-item-title>
              </v-list-item>
            </v-list-item-group>
            <v-divider></v-divider>
            <v-list-item @click="saveDialog= true" >
              <v-list-item-icon>
                <v-icon color="primary">mdi-plus</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title >{{ $t('presets.saveNew') }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </div>
        <v-sheet v-if="activeFilterPresetId" min-width="180" color="surface" class="pa-0 rounded-r">
          <v-list dense color="transparent" min-width="180">
            <v-list-item-group
              :value="isActivePresetNotChanged ? 'undefined' : 'active'"
            >
              <v-list-item
                :value="isActivePresetNotChanged === false ? 'active' : ''"
                :disabled="!(activeFiltersPreset && isActivePresetNotChanged === false)"
                :color="isActivePresetNotChanged === false ? 'warning' : ''"
                icon
                @click="saveActivePreset"
              >
                <v-list-item-icon>
                  <v-icon :color="isActivePresetNotChanged === false ? 'warning' : ''">mdi-content-save-edit</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title >{{ $t('presets.saveChanges') }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-item-group>
            <v-list-item v-if="activeFilterPresetId" @click="renameDialog = true" >
              <v-list-item-icon>
                <v-icon>mdi-account-edit-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title >{{ $t('Rename') }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-item color="primary" @click="removeCurrentItem">
              <v-list-item-icon>
                <v-icon color="error">mdi-trash-can-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title class="error--text" >{{ $t('Delete') }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

          </v-list>
        </v-sheet>
      </v-card>
    </v-menu>

    <v-dialog v-model="saveDialog" width="500">
      <v-card :loading="isLoading" :disabled="isLoading" >
        <v-card-title class="text-h5 nav">
          <v-icon large left>mdi-content-save</v-icon>
          {{ $t('presets.saveNewFilters') }}
        </v-card-title>
        <v-container fluid class="py-3 px-3">
          <v-text-field
            ref="newPresetName"
            v-model="newPresetName"
            :error-messages="newPresetNameErrors"
            label="Name"
            outlined
            required
            type="text"
            @input="$v.newPresetName.$touch()"
            @blur="$v.newPresetName.$touch()"
          ></v-text-field>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="saveNewFilterPreset">{{ $t('Save') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-model="renameDialog" width="500">
      <v-card :loading="isLoading" :disabled="isLoading" >
        <v-card-title class="text-h5 nav">
          <v-icon large left>mdi-content-save</v-icon>
          {{ $t('presets.rename') }}
        </v-card-title>
        <v-container fluid class="py-3 px-3">
          <v-text-field
            ref="newPresetName"
            v-model="presetName"
            :error-messages="newPresetNameErrors"
            label="Name"
            outlined
            required
            type="text"
            @input="$v.newPresetName.$touch()"
            @blur="$v.newPresetName.$touch()"
          ></v-text-field>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="saveActivePreset">{{ $t('Save') }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!--    <v-btn v-if="activeFiltersPreset && isActivePresetNotChanged === false" icon @click="saveActivePreset">-->
    <!--      <v-icon color="success">mdi-content-save-edit</v-icon>-->
    <!--    </v-btn>-->
  </span>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import { minLength, required } from 'vuelidate/lib/validators'

export default {
  name: 'FilterPresetsList',
  components: {
  },
  validations: {
    newPresetName: { required, minLength: minLength(3) }
  },
  data() {
    return {
      renameDialog: false,
      saveDialog: false,
      menu: false,
      editMode: false,
      newPresetName: null
    }
  },
  computed: {
    presetName: {
      get() {
        if (this.activeFiltersPreset && this.activeFiltersPreset.name) return this.activeFiltersPreset.name

        return this.newPresetName
      },
      set(value) {
        if (this.activeFiltersPreset && this.activeFiltersPreset.name) this.activeFiltersPreset.name = value
        this.newPresetName = value
      }
    },
    newPresetNameErrors () {
      const errors = []

      if (!this.$v.newPresetName.$dirty) return errors
      !this.$v.newPresetName.minLength && errors.push('Password must be 3 characters long')
      !this.$v.newPresetName.required && errors.push('Name is required.')

      return errors
    },
    menuWidth() {
      return this.activeFilterPresetId ? 400 : 200
    },
    label() {
      return this.activeFiltersPreset ? (this.activeFiltersPreset.name.substring(0,15)) + (this.activeFiltersPreset.name.length > 15 ? '...' : '') : this.$t('My filters')
    },
    activePresetCloseIcon() {
      if (this.isActivePresetNotChanged) {
        return 'mdi-close'
      }

      return 'mdi-content-save-edit'
    },
    isActivePresetNotChanged() {
      if (this.activeFiltersPreset === null) {
        return false
      }

      return this.activeFiltersPreset.filters === JSON.stringify(this.selectedFilters) &&
          this.activeFiltersPreset.sorting === JSON.stringify(this.sorting)
    },
    items() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    ...mapGetters('screener', ['selectedFilters']),
    ...mapState('screener', ['sorting']),
    ...mapFields('screener', ['activeFiltersPreset']),
    ...mapFields('screener_filters', ['deleted', 'error', 'created', 'updated', 'resetList', 'totalItems', 'view','isLoading']),
    ...mapState('screener_filters', ['totalItems']),
    ...mapGetters('screener_filters', ['list', 'find']),
    ...mapFields('auth', { activeFilterPresetId: 'profile.activeFilterPresetId' })
  },
  methods: {
    saveNewFilterPreset() {
      this.$v.newPresetName.$touch()
      if (this.$v.newPresetName.$pending || this.$v.newPresetName.$error) return
      const newPreset = {
        name: this.newPresetName,
        filters: JSON.stringify(this.selectedFilters),
        sorting: JSON.stringify(this.sorting)
      }

      this.createItem(newPreset).then(() => {
        this.isLoading = false
        this.saveDialog = false
        this.newPresetName = null
      })

    },
    onPresetClick(item) {
      this.activeFilterPresetId = item['@id']
      this.activeFiltersPreset = item
      this.menu = false
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
        this.renameDialog = false
      })
    },
    removeCurrentItem() {
      this.removeFilterPreset(this.activeFiltersPreset)
    },
    removeFilterPreset(item) {
      this.$confirm('Do you really want to delete this item?',{ color: 'error', title: 'Confirmation', icon: 'mdi-alert' }).then((res) => {
        if (res) {
          this.isLoading = true
          this.deleteItem(item).then(() => {
            if (this.itemIsActivePreset(item)) {
              this.activeFilterPresetId = null
              this.activeFiltersPreset = null
            }
            this.isLoading = false
            this.getAllPresets()
          })
        }
      })
    },
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
      deleteItem: 'del',
      updateItem: 'update',
      fetchAll: 'fetchAll',
      createItem: 'create'
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
