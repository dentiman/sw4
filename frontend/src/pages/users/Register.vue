<template>
  <div>
    <v-alert v-if="error" type="error">{{ error }}</v-alert>
    <div v-if="success === false && authorized ===false">
      <v-card
        outlined
        class="px-3"
        :loading="isLoading"
        :disabled="isLoading"
      >
        <v-card-title class="pt-3">
          <v-divider class="my-1"></v-divider>
          <div class="mx-2">{{ $t('register.register') }}</div>
          <v-divider class="my-1"></v-divider>
        </v-card-title>
        <v-card-text>
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

          <v-btn
            class="mt-3 elevation-0"
            x-large
            block
            :disabled="!valid"
            color="success"
            @click="validate"
          >{{ $t('register.register') }}</v-btn>
          <div class="text-center mt-2 text-body-2">
            {{ $t('register.doHaveAccount') }} <router-link to="/login">{{ $t('register.signIn') }}</router-link>
          </div>
        </v-card-text>
      </v-card>
    </div>

    <v-card
      v-if="success === false && authorized ===true"
      height="280"
      class="px-3"
    >
      <v-row
        class="fill-height"
        align-content="center"
        justify="center"
      >
        <h3 class="font-weight-black success--text  pt-3 text-md-center">
          {{ $t('register.haveAccount') }}
        </h3>
        <v-col
          class="text-sm-body-1 text-center"
          cols="12"
        >
          {{ $t('register.haveAccount2') }}
        </v-col>
        <v-col cols="12">
          <v-btn color="error" block class="text-md-center mt-3" @click="logout">{{ $t('register.logout') }}</v-btn>
          <v-btn text block to="/" class="text-md-center mt-3">{{ $t('register.home') }}</v-btn>
        </v-col>
      </v-row>
    </v-card>

    <v-card
      v-if="success"
      height="250"
    >
      <div class="bg-holder bg-card" style="background-image:url('/asset/img/illustrations/corner-2.png')"></div>
      <v-row
        class="fill-height"
        align-content="center"
        justify="center"
      >
        <h3 class="font-weight-black success--text  pt-3 text-md-center">
          <v-icon color="success">mdi-account-check</v-icon>
          {{ $t('register.success') }}
        </h3>
        <v-col
          class="text-sm-body-1 text-center"
          cols="12"
        >
          {{ $t('register.setup') }}
        </v-col>
        <v-col cols="6">
          <v-progress-linear
            color="success"
            indeterminate
            rounded
            height="6"
          ></v-progress-linear>
          <v-btn text to="/" class="text-md-center mt-3">{{ $t('register.home') }}</v-btn>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
// import { mapFields } from 'vuex-map-fields';
//  import { mapState } from 'vuex';
// import {mapFields} from "vuex-map-fields";

import axios from '@/utils/interceptor'
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'Register',
  computed: {
    ...mapGetters('auth', { authorized: 'authorized' }),
    passwordMatch() {
      return () => this.password === this.verify || 'Password must match'
    }
  },
  methods: {
    ...mapActions('auth', ['loadProfile','logout']),
    validate() {
      if (this.$refs.registerForm.validate()) {
        this.isLoading = true
        axios
          .post('api/register', { plainPassword: this.password, email: this.email })
          .then((response) => response.data)
          .then(() => {
            this.success = true
            this.$store.dispatch('auth/login', {
              'email': this.email,
              'password': this.password
            }).then( () => {

            })

          })
          .catch((error) => {
            if (
              error.response &&
                  error.response.status &&
              (error.response.status === 400 || error.response.status === 422) &&
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
  data: () => ({
    success: false,
    isLoading: false,
    error: null,
    valid: true,
    email: '',
    password: '',
    verify: '',
    emailRules: [
      (v) => !!v || 'Required',
      (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid'
    ],

    show1: false,
    rules: {
      required: (value) => !!value || 'Required.',
      min: (v) => (v && v.length >= 8) || 'Min 8 characters'
    }
  }),
  watch: {
    authorized(value) {
      if (value) {
        this.loadProfile().then(() => {
          this.$router.push('/')
        }
        )
      }
    }
  },
  head: {
    title: {
      inner: 'Register'
    }
  }
}

</script>

<style scoped>

</style>
