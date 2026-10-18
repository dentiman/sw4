<template>
  <v-alert
    :value="model"
    :type="alert.type"
    border="left"
    class="mx-3 mb-1 not-alert"
    :transition="model ? `scroll-x-transition` : `scroll-x-reverse-transition`"
  >
    {{ alert.text }}
  </v-alert>
</template>

<script>
import store from '@/store'

export default {
  name: 'NotificationAlert',
  props: {
    alert: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      timeout: 4000,
      model: false
    }
  },
  mounted() {
    this.model = true
    setTimeout(() => {
      this.model = false
    },this.timeout
    )
    setTimeout(() => {
      store.commit('notifications/remove', this.alert.id )
    } ,this.timeout + 1500
    )
  }
}
</script>

<style scoped>
.theme--dark.v-alert.v-sheet,
.theme--light.v-alert.v-sheet
{
      color: white !important;
    }
</style>
