<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="items"
      :page.sync="options.page"
      :sort-by.sync="options.sortBy"
      :sort-desc.sync="options.sortDesc"
      :server-items-length="totalItems"
      :items-per-page="items.length"
      :loading="loading"
      class="elevation-1"
      hide-default-footer
    >
      <template v-slot:loading>
        <v-skeleton-loader
          ref="skeleton"
          type="table-tbody"
          class="mx-auto"
        ></v-skeleton-loader>
      </template>
      <template v-slot:footer>
        <v-pagination
          v-model="options.page"
          :length="pageCount"
          :total-visible="7"
        ></v-pagination>
      </template>
    </v-data-table>
  </div>
</template>

<script>

import CrudGridMixin from '@/mixins/crud/CrudGridMixin'
export default {
  mixins: [CrudGridMixin],
  data () {
    return {
      apiResourceUrl: 'quote',
      headers: [
        {
          text: 'Dessert (100g serving)',
          align: 'start',
          sortable: false,
          value: 'username'
        },
        { text: '@Id', value: '@id' },
        { text: '@Id', value: 'id' },
        { text: 'price', value: 'price' }
      ]
    }
  }
}
</script>

<style scoped>

</style>
