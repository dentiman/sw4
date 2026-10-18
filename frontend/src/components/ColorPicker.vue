<template>
  <v-menu
    v-model="menu"
    top
    nudge-bottom="105"
    nudge-left="16"
    :close-on-content-click="false"
  >
    <template v-slot:activator="{ on }">
      <v-avatar
        :color="color"
        size="38"
        v-on="on"
      ></v-avatar>

    </template>
    <v-card>
      <v-card-text class="pa-0">
        <v-color-picker  v-model="color" flat  />
      </v-card-text>
    </v-card>
  </v-menu>
</template>

<script>
export default {
  name: 'ColorPicker',
  props: {
    patch: {
      require: true
    }
  },
  data: () => ({
    mask: '!#XXXXXXXX',
    menu: false
  }),
  computed: {
    color: {
      get() {
        return this.$store.getters['screener/getField'](this.patch)
      },
      set(value) {
        this.$store.commit('screener/updateField', {
          path: this.patch,
          value: value
        })
      }
    },
    swatch() {
      const { color } = this

      return {

        backgroundColor: color

      }
    },
    swatchStyle() {
      const { color, menu } = this

      return {
        'margin': '-4px 0 0 10px',
        display: 'inline-block',
        backgroundColor: color,
        cursor: 'pointer',
        height: '30px',
        width: '30px',
        borderRadius: menu ? '50%' : '4px',
        transition: 'border-radius 200ms ease-in-out',
        position: 'absolute'
      }
    }
  }
}
</script>

<style scoped>

</style>
