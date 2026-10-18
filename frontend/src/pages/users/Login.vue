<template>
  <div>
    <v-card
      class="px-3 pb-3"
      :loading="loading"
      :disabled="loading"
    >
      <v-card-title class="pt-3">
        <v-divider class="my-1"></v-divider>
        <div class="mx-2">{{ $t('register.signIn') }}</div>
        <v-divider class="my-1"></v-divider>
      </v-card-title>
      <div class="text-center">
        <v-alert v-if="error" type="error">{{ error }}</v-alert>
      </div>
      <v-card-text>
        <v-text-field
          v-model="email"
          outlined
          name="login"
          :error-messages="emailErrors"
          label="E-mail"
          required
          type="text"
          @input="$v.email.$touch()"
          @blur="$v.email.$touch()"
        ></v-text-field>

        <v-text-field
          id="password"
          v-model="password"
          outlined
          label="Password"
          :error-messages="passwordErrors"
          name="password"
          type="password"
          required
        ></v-text-field>
        <v-btn block class="success elevation-0" x-large @click="login">Sign In</v-btn>
        <div class=" mt-3 text-body-2">
          <router-link to="reset" > {{ $t('register.forget_password') }}</router-link>
          <router-link to="register" class="float-right"> {{ $t('register.register') }}</router-link>
        </div>
      </v-card-text>
    </v-card>
  </div>
</template>

<script>
// import { mapFields } from 'vuex-map-fields';
//  import { mapState } from 'vuex';
// import {mapFields} from "vuex-map-fields";
import axios from '@/utils/interceptor'
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
import * as types from '@/store/auth/mutation_types'
import store from '../../store'
export default {
  name: 'Login',
  mixins: [validationMixin],
  data() {
    return {
      error: null,
      email: null,
      password: null,
      loading: false
    }
  },
  validations: {
    password: { required },
    email: { required, email }
  },
  computed: {
    step: {
      get() {
        return this.$store.state.auth.step
      },
      set(value) {
        this.$store.commit('auth/AUTH_SET_STEP', value)
      }
    },
    dialog: {
      get() {
        return this.$store.state.auth.showLoginDialog
      },
      set(value) {
        this.$store.commit('auth/AUTH_TOGGLE_DIALOG', value)
      }
    },
    emailError: {
      get() {
        return this.$store.state.auth.emailError
      },
      set(value) {
        this.$store.commit('auth/AUTH_SET_EMAIL_ERROR', value)
      }
    },
    passwordError: {
      get() {
        return this.$store.state.auth.emailError
      },
      set(value) {
        this.$store.commit('auth/AUTH_SET_PASSWORD_ERROR', value)
      }
    },
    passwordErrors () {
      const errors = []

      if (!this.$v.password.$dirty) return errors
      !this.$v.password.required && errors.push('Password is required.')
      this.passwordError && errors.push(this.passwordError)

      return errors
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
  methods: {
    login() {
      global.console.log(this.$route.query['redirect'])
      this.$v.email.$touch()
      if (this.$v.email.$pending || this.$v.email.$error) return
      this.$v.password.$touch()
      if (this.$v.password.$pending || this.$v.password.$error) return
      this.loading = true
      axios
        .post( 'api/authentication-token', {
          'email': this.email,
          'password': this.password
        })
        .then((response) => response.data)
        .then((data) => {
          this.$store.commit('auth/AUTH_UPDATE_TOKEN',data)
          this.loading = false
          if (this.$route.query['redirect']) {
            this.$router.push(this.$route.query['redirect'])
          } else {
            this.$router.push('/')
          }

        })
        .catch((e) => {
          if (
            e.response &&
            e.response.status &&
            e.response.status === 401 &&
            e.response.data.message === 'Invalid credentials.'
          ) {
            this.error = 'Invalid credentials.'
          } else {
            this.error = e.message
          }
          this.loading = false
        })

    },
    reset() {
      this.$v.$reset()
      this.password = ''
      this.email = ''
      this.error = ''
    }
  },
  head: {
    title: {
      inner: 'Sign In'
    }
  }
}
</script>

<style scoped>

</style>
