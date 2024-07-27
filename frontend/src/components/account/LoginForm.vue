<template>
  <div class="text-center">
    <v-dialog v-model="dialog"  :transition="false" width="400">
      <v-card
        class="mx-auto"
        outlined
        :loading="dialogLoading"
        :disabled="dialogLoading"
      >
        <v-stepper v-model="step" >
          <template>
            <v-stepper-items>
              <v-stepper-content :step="1" >
                <v-card-text>
                  <p class="headline text-center">
                    Login
                  </p>
                </v-card-text>
                <v-form class="py-3 px-2">
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

                </v-form>
                <v-card-actions>
                  <a href="/reset" style="text-decoration:none">
                    {{ $t('register.forget_password') }}
                  </a>
                  <v-spacer></v-spacer>
                  <v-btn color="primary" @click="retrieveUser">
                    Next
                  </v-btn>
                </v-card-actions>
              </v-stepper-content>
              <v-stepper-content :step="2">
                <v-form>
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
                  <v-btn color="primary mt-1" @click="login">
                    Sign In
                  </v-btn>
                </v-form>
              </v-stepper-content>
            </v-stepper-items>
          </template>
        </v-stepper>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
// import { mapFields } from 'vuex-map-fields';
//  import { mapState } from 'vuex';
// import {mapFields} from "vuex-map-fields";
import { validationMixin } from 'vuelidate'
import { required, email } from 'vuelidate/lib/validators'
export default {
  name: 'LoginForm',
  mixins: [validationMixin],
  data() {
    return {
      email: null,
      password: null,
      dialogLoading: false
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
    // ...mapState('auth',{
    //     dialog: state => state.showLoginDialog,
    // }),

    // dialog() {
    //     return this.$store.state.showLoginDialog;
    // },
    // error() {
    //     return this.$store.getters['auth/error']
    // },
  },
  watch: {
    dialog(val) {
      if (val === false) {
        this.reset()
      }
    }
  },

  methods: {
    retrieveUser() {
      this.$v.email.$touch()
      if (this.$v.email.$pending || this.$v.email.$error) return
      this.dialogLoading = true
      this.$store.dispatch('auth/exist', {
        'email': this.email
      }).then( () => {
        this.dialogLoading = false
      })

    },
    login() {
      this.$v.password.$touch()
      if (this.$v.password.$pending || this.$v.password.$error) return
      this.dialogLoading = true
      this.$store.dispatch('auth/login', {
        'email': this.email,
        'password': this.password
      }).then( () => {
        this.dialogLoading = false
      })

    },
    reset() {
      this.$v.$reset()
      this.password = ''
      this.email = ''
      this.step = 1
    }
  }
}
</script>

<style scoped>

</style>
