<template>
  <v-footer color="transparent">
    <v-container class="py-5">
      <v-row>
        <v-col cols="12" md="4">
          <div class="text-h6 text-lg-h5 font-weight-bold">{{ $t('label.navigation') }}</div>
          <div style="width: 80px; height: 4px" class="mb-5 mt-1 secondary" />
          <div class="d-flex flex-wrap">
            <div v-for="(link, i) in links" :key="i" class="w-half body-1 mb-1">
              <router-link
                v-if="link.to"
                class="text-decoration-none secondary--text text--lighten-2"
                :to="link.to"
              >{{ link.label }}</router-link>
              <a
                v-else
                class="text-decoration-none secondary--text text--lighten-2"
                :href="link.href"
                :target="link.target || 'blank'"
              >{{ link.label }}</a>
            </div>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="text-h6 text-lg-h5 font-weight-bold">{{ $t('label.contact_info') }}</div>
          <div style="width: 80px; height: 4px" class="mb-5 mt-1 secondary" />

          <div class="d-flex mb-2">
            <v-icon color="secondary lighten-1" class="mr-2">mdi-email-outline</v-icon>
            <a href="mailto:support@stock-watcher.com" class="text-decoration-none secondary--text text--lighten-2">support@stock-watcher.com</a>
          </div>
        </v-col>
        <v-col cols="12" md="4">
          <div class="text-h6 text-lg-h5 font-weight-bold">{{ $t('label.we_accept') }}</div>
          <div style="width: 80px; height: 4px" class="mb-5 mt-1 secondary" />
          <v-img
            src="/images/gateways/liqpay.png"
            width="100"
            class="mb-1"
          />
<!--          <v-img-->
<!--            src="/images/gateways/webmoney.png"-->
<!--            width="100"-->
<!--          />-->
        </v-col>
      </v-row>
      <v-divider class="my-3"></v-divider>
      <div class="text-center caption">
        © stock-watcher.com {{ new Date().getFullYear() }}. {{ $t('label.all-rights-reserved') }} <br> {{ version }}
      </div>
    </v-container>
  </v-footer>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { email, required } from 'vuelidate/lib/validators'
export default {
  name: 'Footer',
  data() {
    return {
      email: null,
      subscribed: false,
      links: [{
        label: this.$t('label.screener'),
        to: '/'
      }, {
        label: this.$t('label.premium'),
        to: '/premium'
      }, {
        label: this.$t('premium.policy'),
        to: '/policy'
      }, {
        label: this.$t('premium.terms'),
        to: '/terms'
      }]
    }
  },
  computed: {
    version() {
      return process.env.VUE_APP_VERSION
    },
    emailErrors () {
      const errors = []

      if (!this.$v.email.$dirty) return errors
      !this.$v.email.email && errors.push('Must be valid e-mail')
      !this.$v.email.required && errors.push('E-mail is required')
      this.emailError && errors.push(this.emailError)

      return errors
    }
  },
  validations: {
    emailError: false,
    email: { required, email }
  },
  methods: {
    subscribe() {
      this.$v.email.$touch()
      if (this.$v.email.$pending || this.$v.email.$error) return
      this.subscribed = true
    }
  }
}
</script>
