<template>
  <div>
    <v-card
      v-if="success === false "
      class="px-3 pb-3"
      outlined
      :loading="isLoading"
      :disabled="isLoading"
    >
      <div class="text-center pt-3">
        <v-alert v-if="error" type="error">{{ error }}</v-alert>
        <v-alert v-if="success" type="success">{{ $t('register.success_reset') }}</v-alert>
      </div>

      <v-card-text>
        <h2 class=" pt-3 pb-3  text-md-center">
          {{ $t('register.resetTitle') }}
        </h2>
        <p class="pb-3  text-center">
          {{ $t('register.resetText') }}
        </p>
        <v-form ref="registerForm" v-model="valid" autocomplete="new" lazy-validation>
          <v-text-field
            v-model="email"
            autofocus
            autocomplete="username"
            clearable
            outlined
            :rules="emailRules"
            :label="$t('register.email')"
            required
          ></v-text-field>

        </v-form>
      </v-card-text>
      <v-btn
        class="mt-3"
        x-large
        block
        :disabled="!valid"
        color="success"
        @click="validate"
      >{{ $t('register.resetRequest') }}</v-btn>
    </v-card>

    <v-card
      v-if="success"
      class="pa-3 mx-auto"
    >
      <v-card-text>
        <p class="pt-3  text-center">
          {{ $t('register.resetSuccess') }}
        </p>
      </v-card-text>
    </v-card>

    <div class="mt-4 text-center">
      <router-link to="/" class="grey-link" >
        {{ $t('register.home') }}</router-link>
    </div>
  </div>
</template>

<script>

import axios from '@/utils/interceptor'
export default {
  name: 'ResetPassword',
  data: () => ({
    success: false,
    isLoading: false,
    error: null,
    valid: true,
    email: '',
    emailRules: [
      (v) => !!v || 'Required',
      (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid'
    ]
  }),
  computed: {
    passwordMatch() {
      return () => this.password === this.verify || 'Password must match'
    }
  },
  methods: {
    validate() {
      if (this.$refs.registerForm.validate()) {
        this.isLoading = true
        this.error = null
        axios
          .post('api/reset-password/request', { url: location.host + '/reset/validate' , email: this.email })
          .then((response) => response.data)
          .then(() => {
            this.success = true

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
            this.isLoading = false
          }
          )
      }
    },
    reset() {
      this.$refs.registerForm.reset()
      this.error = null
    },
    resetValidation() {
      this.$refs.registerForm.resetValidation()
    }
  },
  head: {
    title: {
      inner: 'Reset Password'
    }
  }
}

</script>

<style scoped>

</style>
