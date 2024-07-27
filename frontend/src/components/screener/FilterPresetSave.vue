<template>
  <v-menu
    v-model="saveAsMenu"
    :close-on-content-click="false"
    bottom
    right
    left
    max-width="290"
    offset-y
  >
    <template v-slot:activator="{ on }">
      <v-btn
        fab
        x-small
        class="px-1 mr-1 mb-1"
        v-on="on"
      >
        <v-icon size="20">mdi-content-save</v-icon>
      </v-btn>
    </template>
    <v-card :width="290" :loading="isLoading" :disabled="isLoading" >
      <div class="bg-holder bg-card" style="background-image:url('/images/illustrations/corner-3.png')"></div>
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
        <v-btn color="primary" @click="saveNewFilterPreset">OK</v-btn>
      </v-card-actions>
    </v-card>
  </v-menu>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import { mapFields } from 'vuex-map-fields'
import { validationMixin } from 'vuelidate'
import { minLength, required } from 'vuelidate/lib/validators'
//item = { name:'ff', query: 'ff'}
export default {
  name: 'FilterPresetSave',
  mixins: [validationMixin],
  props: {
    selectedFilters: {
      type: Object,
      required: true
    }
  },
  validations: {
    newPresetName: { required, minLength: minLength(3) }
  },
  data() {
    return {
      saveAsMenu: false,
      presetsMenu: false,
      newPresetName: null
    }
  },
  computed: {
    items() {
      return this.list.filter((i) => {
        return i && i['@id']
      })
    },
    newPresetNameErrors () {
      const errors = []

      if (!this.$v.newPresetName.$dirty) return errors
      !this.$v.newPresetName.minLength && errors.push('Password must be 3 characters long')
      !this.$v.newPresetName.required && errors.push('Name is required.')

      return errors
    },
    ...mapState('screener', ['sorting']),
    ...mapFields('screener', ['activeFiltersPreset']),
    ...mapGetters('screener_filters', {
      list: 'list'
    }),
    ...mapFields('screener_filters', ['deleted','error', 'isLoading', 'created','updated','resetList','totalItems','view']),
    ...mapFields('auth', { activeFilterPresetId: 'profile.activeFilterPresetId' })
  },
  methods: {
    focusOnName() {
      this.$refs.newPresetName.focus()
    },
    onCreated(item) {
      this.getAllPresets()
      this.saveAsMenu = false
      this.activeFiltersPreset = item
      this.activeFilterPresetId = item['@id']
    },
    getAllPresets() {
      this.resetList = true
      this.fetchAll()
    },
    saveNewFilterPreset() {
      this.$v.newPresetName.$touch()
      if (this.$v.newPresetName.$pending || this.$v.newPresetName.$error) return
      const newPreset = {
        name: this.newPresetName,
        filters: JSON.stringify(this.selectedFilters),
        sorting: JSON.stringify(this.sorting)
      }

      this.createItem(newPreset)
      //after save need to fetch all with new preset

      //  this.setActivePreset() set by Id

    },
    ...mapActions('screener_filters', {
      fetchAll: 'fetchAll',
      deleteItem: 'del',
      updateItem: 'update',
      createItem: 'create'
    })
    // updateItem(item)
  },
  watch: {
    saveAsMenu(saveAsMenu) {
      if (!saveAsMenu) {
        this.newPresetName = null
        this.$v.newPresetName.$reset()
      }
    },
    created(created) {
      if (!created) {
        return
      }
      this.onCreated(created)
    }
  }
}
</script>

<style scoped>

</style>
