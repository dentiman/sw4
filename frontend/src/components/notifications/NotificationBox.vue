<template>
  <v-card flat color="transparent" class="mb-10" >
    <notification-alert
      v-for="(alert, index) in alerts"
      :key="index"
      :alert="alert"
    >
      {{ alert.text }}
    </notification-alert>
  </v-card>
</template>

<script>
import { mapGetters } from 'vuex'
import NotificationAlert from '@/components/notifications/NotificationAlert'
export default {
  name: 'NotificationBox',
  components: {
    NotificationAlert
  },
  computed: {
    ...mapGetters('notifications', { alerts: 'list' })
  }
}
</script>

<style scoped>

</style>
