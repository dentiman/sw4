<template>
  <div>
    <v-card
      v-if="success === false"
      outlined
      class="px-3 pb-3 mx-auto"
      :loading="isLoading"
      :disabled="isLoading"
    >
      <v-card-text class=" pt-3">
        <v-alert v-if="error" type="error">{{ error }}</v-alert>

        <h2 class=" pt-3 pb-3  text-md-center">
          {{ $t('register.resetTitle') }}
        </h2>
        <p class="pb-3  text-center">
          {{ $t('register.newPasswordText') }}
        </p>
        <v-form ref="registerForm" v-model="valid" autocomplete="new" lazy-validation>
          <v-text-field
            v-model="password"
            autocomplete="new-password"
            clearable
            outlined
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            name="input-10-1"
            :label="$t('register.password')"
            hint="At least 8 characters"
            counter
            @click:append="show1 = !show1"
          ></v-text-field>
          <v-text-field
            v-model="verify"
            clearable
            outlined
            :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, passwordMatch]"
            :type="show1 ? 'text' : 'password'"
            name="input-10-1"
            :label="$t('register.confirm')"
            counter
            @click:append="show1 = !show1"
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

      <v-row class="text-center mt-3">
        <v-col cols="6"> <a class="grey-link" @click="reset">{{ $t('register.reset') }}</a></v-col>
        <v-col cols="6"> <router-link class="grey-link" to="/" >{{ $t('register.home') }}</router-link></v-col>
      </v-row>
    </v-card>

    <v-card
      v-if="success"
      class="px-3 pb-3"
    >
      <v-card-text>
        <p class="pt-3  text-center">
          {{ $t('register.newPasswordSetText') }}
        </p>
      </v-card-text>
    </v-card>

  </div>
</template>

<script>
// import { mapFields } from 'vuex-map-fields';
//  import { mapState } from 'vuex';
// import {mapFields} from "vuex-map-fields";

import axios from '@/utils/interceptor'

export default {
  name: 'ResetPasswordFinish',
  data: () => ({
    token: null,
    success: false,
    isLoading: false,
    error: null,
    valid: true,
    password: '',
    show1: false,
    verify: '',
    rules: {
      required: (value) => !!value || 'Required.',
      min: (v) => (v && v.length >= 8) || 'Min 8 characters'
    }
  }),
  computed: {
    passwordMatch() {
      return () => this.password === this.verify || 'Password must match'
    }
  },
  created() {
    global.console.log(this.$route.params)
    this.token = this.$route.params.token
  },
  methods: {
    validate() {
      if (this.$refs.registerForm.validate()) {
        this.isLoading = true
        axios
          .post('/api/reset-password/reset', { password: this.password, token: this.token })
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
