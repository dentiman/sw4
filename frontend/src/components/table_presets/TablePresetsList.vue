<template>
  <div>
    <v-chip
      :disabled="isChartSettingMode || emptyResult"
      v-for="(preset, index) in items"
      :key="index"
      close
      close-icon="mdi-cog"
      :color="itemIsActivePreset(preset) ? 'primary' : ''"
      class="mr-1 pl-1 text-uppercase font-weight-black view-item-chip"
      label
      @click="activatePreset(preset)"
      @click:close="openSettings(preset)"
    >
      {{ preset.name }}
    </v-chip>

    <v-dialog
      v-model="showTableSettingDialog"
      max-width="900"
      :transition="false"

    >
      <v-card :loading="isLoading" :disabled="isLoading" >
        <v-card-title class="text-h5 nav">
          <v-icon
            large
            left
          >
            mdi-cog
          </v-icon>
          {{ $t('settings.table_presets') }}
        </v-card-title>
        <v-card-text class="mb-0 pb-0">
          <v-text-field
            ref="newPresetName"
            v-model="editedTablePreset.name"
            outlined
            dense
            :error-messages="newPresetNameErrors"
            :label="$t('Name')"
            required
            type="text"
            class="mt-2 mb-0"
            style="max-width: 220px;"
            placeholder="Name is required"
            @input="$v.editedTablePreset.name.$touch()"
            @blur="$v.editedTablePreset.name.$touch()"
          ></v-text-field>
        </v-card-text>

        <v-card-text class="mb-0 pb-0" >
          <v-tabs v-model="tabs" color="info" centered >
            <v-tab
              v-for="(value, name) in columnsGroups"
              :key="name"
              :href="`#tab-${name}`"
            >
              {{ $t('filters.group.' + name) }}
            </v-tab>
          </v-tabs>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-text style="min-height:250px;background-color: var(--v-nav-base)" class="pt-2">
          <v-tabs-items v-model="tabs">
            <v-tab-item
              v-for="(filtersInGroup, name) in columnsGroups"
              :key="name"
              :value="'tab-' + name"
            >
              <v-container fluid>
                <v-row>
                  <v-col v-for="filterObj in filtersInGroup" :key="filterObj.filterId" cols="3" class="py-0">
                    <v-checkbox
                      v-model="editedTablePreset.fields"
                      :label="$t(`filters.${filterObj.filterId}.label`)"
                      :value="filterObj.filterId"
                      class="my-0"
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-container>
            </v-tab-item>
          </v-tabs-items>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            text
            @click="showTableSettingDialog = false"
          >{{ $t('Cancel') }}</v-btn>
          <v-btn
            v-if="editedTablePreset && editedTablePreset['@id']"
            text
            color="red_c1"
            @click="removePreset(editedTablePreset)"
          >{{ $t('Delete') }}</v-btn>
          <v-btn
            color="green darken-1"
            text
            @click="savePreset"
          >{{ $t('Save') }}</v-btn>
        </v-card-actions>

      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import { minLength, required } from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
import filterSerialize from '@/utils/filters/filterSerialize'

export default {
  name: 'TablePresetsList',
  mixins: [validationMixin],
  data() {
    return {
      tabs: null,
      columnsGroups: {},
      editedTablePreset: {
        name: null,
        fields: []
      }
    }
  },
  validations: {
    editedTablePreset: {
      name: { required, minLength: minLength(3) }
    }

  },
  computed: {
    newPresetNameErrors () {
      const errors = []

      if (!this.$v.editedTablePreset.name.$dirty) return errors
      !this.$v.editedTablePreset.name.minLength && errors.push('Must be 3 characters long')
      !this.$v.editedTablePreset.name.required && errors.push('Name is required.')

      return errors
    },
    items() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    ...mapGetters('screener', ['emptyResult','isChartSettingMode']),
    ...mapState('table_layouts', ['created','updated','totalItems']),
    ...mapState('screener', ['view','defaultTableFields']),
    ...mapFields('screener', ['activeTablePreset','showTableSettingDialog']),
    ...mapFields('screener', {
      activeTablePresetFields: 'activeTablePreset.fields',
      activeTablePresetName: 'activeTablePreset.name'
    }),
    ...mapGetters('table_layouts', ['list','find']),
    ...mapFields('table_layouts', ['isLoading']),
    ...mapFields('auth', { activeTablePresetId: 'profile.activeTablePresetId' })
  },
  methods: {
    openSettings(preset) {
      this.showTableSettingDialog = true
      this.editedTablePreset = { ...preset }
    },
    activatePreset(preset) {
      this.activeTablePresetId = preset['@id']
      this.activeTablePreset = preset
      this.setView('table')
    },
    removePreset(preset) {
      this.$confirm('Do you really want to delete this item?',{ color: 'error', title: 'Confirmation', icon: 'mdi-alert' }).then((res) => {
        if (res) {
          this.deleteItem(preset).then(() => {
            this.activeTablePresetId = null
            this.resetTablePreset()
            this.editedTablePreset = {
              name: null,
              fields: []
            }
            this.showTableSettingDialog = false
          })
        }
      })
    },
    savePreset() {
      this.$v.editedTablePreset.name.$touch()
      if (this.$v.editedTablePreset.name.$pending || this.$v.editedTablePreset.name.$error) return
      if (this.editedTablePreset['@id']) {
        this.updateItem(this.editedTablePreset)
      } else {
        this.createItem(this.editedTablePreset)
      }
    },
    setActivePresetFromLocalStorage() {
      this.activeTablePreset = this.activeTablePresetId ? this.find(this.activeTablePresetId) : null
    },
    itemIsActivePreset(item) {
      return !!(this.activeTablePreset && item['@id'] === this.activeTablePreset['@id'] && this.view === 'table')
    },
    onCreated(item) {
      this.activeTablePresetId =  item['@id']
      this.getAllPresets()
      this.activeTablePreset = item
      this.editedTablePreset.fields = [...this.defaultTableFields]
      this.editedTablePreset.name = null
      this.showTableSettingDialog = false
    },
    getAllPresets() {
      this.resetList = true
      this.fetchAll()
    },
    ...mapActions('screener', {
      resetTablePreset: 'resetTablePreset',
      setView: 'setView'
    }),
    ...mapActions('table_layouts', {
      fetchAll: 'fetchAll',
      deleteItem: 'del',
      createItem: 'create',
      updateItem: 'update'
    })
  },

  // eslint-disable-next-line vue/order-in-components
  watch: {
    showTableSettingDialog(value) {
      if (!value) {
        this.editedTablePreset.fields = [...this.defaultTableFields]
        this.editedTablePreset.name = null
      }
    },
    updated(updated) {
      if (!updated) {
        return
      }
      this.onCreated(updated)
    },
    created(created) {
      if (!created) {
        return
      }
      this.onCreated(created)
    },
    totalItems(totalItems) {
      if (totalItems > 0) {
        this.setActivePresetFromLocalStorage()
      }
    }
  },
  // eslint-disable-next-line vue/order-in-components
  created() {
    this.resetList = true
    this.fetchAll()
    this.columnsGroups = filterSerialize.getGroupedTableColumns()

  }
}
</script>

<style scoped>

</style>
