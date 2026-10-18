<template>
  <div>
    <v-sheet class="bg-sheet pb-8" dark>
      <landing-menu></landing-menu>
      <v-container class="py-3 py-sm-3 text-center">
        <v-responsive max-width="800" class="mx-auto">
          <logo></logo>
          <div class="text-h5 text-lg-h6 mt-1 mb-3 text-white">{{ $t('premium.we_provide') }}</div>
          <h3 class="text-h4 text-sm-h4 mb-1 text-white" style="color: white !important;">{{ $t('premium.feature_title') }}</h3>
        </v-responsive>
      </v-container>
      <v-container class="py-4 py-lg-8">
        <v-row>
          <v-col  cols="12" md="4">
            <v-card outlined>
              <v-img
                src="/images/features/feature_1_1.png"
                height="280"
                class="d-flex align-end justify-end"
              ></v-img>
              <v-responsive max-width="360" min-height="320" class="mx-auto text-center pa-4">
                <div class="text-h5 mt-5">{{ $t('premium.feature_1.title') }}</div>
                <div class="text-h6 mt-5">{{ $t('premium.feature_1.description') }}</div>
              </v-responsive>
            </v-card>
          </v-col>
          <v-col  cols="12" md="4">
            <v-card outlined>
              <v-img
                src="/images/features/feature_2_1.png"
                height="280"
                class="d-flex align-end justify-end"
              ></v-img>
              <v-responsive max-width="360" min-height="320" class="mx-auto text-center pa-4">
                <div class="text-h5 mt-5">{{ $t('premium.feature_2.title') }}</div>
                <div class="text-h6 mt-5">{{ $t('premium.feature_2.description') }}</div>
              </v-responsive>
            </v-card>
          </v-col>
          <v-col  cols="12" md="4">
            <v-card outlined>
              <v-img
                src="/images/features/feautre_3_1.png"
                height="280"
                class="d-flex align-end justify-end"
              ></v-img>
              <v-responsive max-width="360" min-height="320" class="mx-auto text-center pa-4">
                <div class="text-h5 mt-5">{{ $t('premium.feature_3.title') }}</div>
                <div class="text-h6 mt-5">{{ $t('premium.feature_3.description') }}</div>
              </v-responsive>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-sheet>

<!--    <v-responsive class="px-8 px-md-10 mx-auto h-full" max-width="1200">-->
<!--      <div class="d-flex flex-column flex-md-row align-center h-full pb-3">-->
<!--        <div class="mr-md-8 mb-4">-->
<!--          <v-avatar size="140">-->
<!--            <v-img src="/images/team/7.jpeg"></v-img>-->
<!--          </v-avatar>-->
<!--        </div>-->
<!--        <div class="pb-3">-->
<!--          <div class="text-h5 text-lg-h5 font-weight-regular" >"{{ $t('premium.founder.text') }}"</div>-->
<!--          <div class="text-h5 mt-3" >{{ $t('premium.founder.name') }}</div>-->
<!--          <div class="text-h6 secondary&#45;&#45;text">{{ $t('premium.founder.title') }}</div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </v-responsive>-->

    <v-sheet class="d-flex flex-column">
      <v-sheet >
        <v-container class="py-8">
          <div class="text-center">
            <div>
              <div class="text-h3 primary--text">{{ $t('premium.get_started.ready') }}</div>
              <div class="text-h3 mt-1">{{ $t('premium.get_started.lets') }}</div>
            </div>
            <div class="mt-4">
              <v-btn x-large class="my-1 mx-sm-2 w-full w-sm-auto" color="primary" to="/buy">{{ $t('premium.get_started.buy') }}</v-btn>
            </div>
            <div class="mt-3">
<!--              <router-link to="#">Get started now with a FREE trial</router-link>-->
            </div>
          </div>
        </v-container>
      </v-sheet>
    </v-sheet>
    <Footer/>
  </div>
</template>

<script>
import LandingMenu from '@/layouts/LandingMenu'
import Logo from '@/components/Logo'
import Footer from '@/layouts/Footer'
export default {
  name: 'Premium',
  components: {
    Logo,
    LandingMenu,
    Footer
  },
  data() {
    return {
      features: [{
        title: this.$t('premium.feature_1.title'),
        image: '/images/features/feature_1_1.png',
        text: this.$t('premium.feature_1.description'),
        link: {
          title: 'Learn More',
          to: '#'
        }
      }, {
        title: this.$t('premium.feature_2.title'),
        image: '/images/features/feature_2_1.png',
        text: this.$t('premium.feature_2.description'),
        link: {
          title: 'Learn More',
          to: '#'
        }
      }, {
        title: this.$t('premium.feature_3.title'),
        image: '/images/features/feautre_3_1.png',
        text: this.$t('premium.feature_3.description'),
        link: {
          title: 'Learn More',
          to: '#'
        }
      }]
    }
  }
}
</script>

<style scoped>
.bg-sheet {
  margin-bottom: 120px;
  background: linear-gradient(90deg, rgb(54 90 127) 0%, rgb(11 20 38) 100%);
}

@media screen and (min-width: 700px) {
  .bg-sheet {
    margin-bottom: 20px;
  }
}
</style>
