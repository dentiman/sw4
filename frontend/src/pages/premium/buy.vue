<template>
  <div>
    <v-sheet class="bg-sheet pb-0 mb-0" dark>
      <landing-menu></landing-menu>
      <v-container class="py-3 py-sm-3 text-center">
        <v-responsive max-width="800" class="mx-auto">
          <logo></logo>
          <h3 class="text-h4 text-sm-h4 my-4 text-white" style="color: white !important;">{{ $t('premium.get_started.buying') }}</h3>
        </v-responsive>
      </v-container>
    </v-sheet>
    <v-card max-width="750" class="mx-auto " style="margin-top: -50px" >
      <v-responsive max-width="750" class="mx-auto text-center">
        <div class="text-h5 primary--text my-1">{{ $t('premium.choose_period') }}</div>
      </v-responsive>
      <v-item-group v-model="servicePeriodId" mandatory >
        <v-container>
          <v-row>
            <v-col
              v-for="(serviceVariant, n) in serviceVariants"
              :key="n"
              cols="12"
              md="4"
              class="py-1"
            >
              <v-item v-slot="{ active, toggle }" :value="serviceVariant['@id']">
                <v-card
                  :color="active ? 'primary' : 'surface'"
                  class="text-center"
                  dark
                  height="85"
                  @click="toggle"
                >
                  <div class="text-h6 font-weight-bold text-uppercase" :style="{ color: active ? 'white': ''}" >
                    {{ $t(serviceVariant.name) }}</div>
                  <v-scroll-y-transition>
                    <div class="d-flex justify-center  ml-n3" :style="{ color: active ? 'white': ''}">
                      <div class="font-weight-regular text-h4 mr-1" style="margin-top: 4px">$</div>
                      <div class="text-h2">{{ serviceVariant.price }}</div>
                    </div>
                  </v-scroll-y-transition>
                </v-card>
              </v-item>
            </v-col>
          </v-row>
        </v-container>
      </v-item-group>
      <v-divider class="my-1"></v-divider>
      <v-responsive max-width="750" class="mx-auto text-center">
        <div class="text-h5 primary--text ">{{ $t('premium.payment_method') }}</div>
      </v-responsive>
      <v-item-group v-model="gatewayConfigId" mandatory >
        <v-container v-if="gatewayConfigs.length" >
          <v-row>
            <v-col
              v-for="(gatewayConfig, n) in gatewayConfigs"
              :key="n"
              cols="12"
              md="4"
              class="py-1"
            >
              <v-item v-slot="{ active, toggle }" :value=" gatewayConfig.id">
                <v-card
                  :color="active ? 'primary' : 'surface'"
                  class="d-flex align-center"
                  dark
                  height="100"
                  @click="toggle"
                >

                  <v-scroll-y-transition>
                    <div
                      class="text-h5 flex-grow-1 text-center"
                      :style="{ color: active ? 'white': ''}"
                    >
                      {{ gatewayConfig.label }}
                      <v-img
                        :src="`/images/gateways/${gatewayConfig.factoryName}.png`"
                        height="50"
                        class="d-flex align-end justify-end mx-1"
                      />
                    </div>
                  </v-scroll-y-transition>
                </v-card>
              </v-item>
            </v-col>
          </v-row>
        </v-container>
      </v-item-group>

      <v-divider class="my-2"></v-divider>
      <v-card-title class="text-center">
        <v-btn
          x-large
          block
          :loading="buttonIsLoading"
          :disabled="buttonIsLoading"
          color="success"
          @click="subscribe"
        >
          {{ $t('premium.get_started.pay') }}
        </v-btn>

        <div class="mt-3 text-center">
          {{ $t('premium.by_pressing') }}
          <router-link to="/terms" >{{ $t('premium.terms_for') }}</router-link>
        </div>
        <div class=" mt-0 text-center">
          {{ $t('premium.by_pressing2') }}
          <router-link to="/policy" >{{ $t('premium.policy_for') }}</router-link>
        </div>

        <notification-box class="mt-1"></notification-box>

      </v-card-title>

    </v-card>
    <Footer/>
  </div>
</template>

<script>
import LandingMenu from '@/layouts/LandingMenu'
import Logo from '@/components/Logo'
import Footer from '@/layouts/Footer'
import axios from '@/utils/interceptor'
import NotificationBox  from '@/components/notifications/NotificationBox'
export default {
  name: 'BuyPremium',
  components: {
    NotificationBox,
    Logo,
    LandingMenu,
    Footer
  },
  data () {
    return {
      error: false,
      buttonIsLoading: false,
      serviceVariantsLoading: true,
      gatewayConfigsLoading: true,
      servicePeriodId: null,
      gatewayConfigId: null,
      serviceVariants: [],
      gatewayConfigs: []
    }
  },
  computed: {
    language() {
      return this.$store.state.auth.profile.language
    }
  },
  created() {
    axios
      .get( '/api/service_variants')
      .then((response) => response.data)
      .then((data) => {
        this.serviceVariants  = data['hydra:member']
        this.servicePeriodId = this.serviceVariants[0] ? this.serviceVariants[0]['@id'] : null
      }).finally(() => {
        this.serviceVariantsLoading = false
      })

    axios
      .get( '/api/gateway_configs')
      .then((response) => response.data)
      .then((data) => {
        this.gatewayConfigs  = data['hydra:member']
        this.gatewayConfigId = this.gatewayConfigs[0] ? this.gatewayConfigs[0]['id'] : null
      }).finally(() => {
        this.gatewayConfigsLoading = false
      })
  },
  methods: {
    subscribe() {
      this.buttonIsLoading = true
      this.error = null
      axios
        .post('api/orders', { gatewayConfigId: this.gatewayConfigId, serviceVariant: this.servicePeriodId })
        .then((response) => response.data)
        .then((data) => {
          window.location.href = data.targetUrl
        })
        .catch((error) => {
          if (
            error.response &&
            error.response.status &&
            error.response.status === 400 &&
            error.response.data['hydra:description']
          ) {
            this.error = error.response.data['hydra:description']
          } else {
            this.error = error.message
          }
        }).finally(() => {
          this.buttonIsLoading = false
        })
    }
  }
}
</script>

<style scoped>
.bg-sheet {
  margin-bottom: 120px;
  background: linear-gradient(90deg, rgb(54 90 127) 0%, rgb(11 20 38) 100%);
}
</style>
