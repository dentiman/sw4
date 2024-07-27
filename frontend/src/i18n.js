import Vue from 'vue'
import VueI18n from 'vue-i18n'
import messagesEn from './locales/en'
import messagesRu from './locales/ru'
import store from './store'
Vue.use(VueI18n)

export default new VueI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en: messagesEn,
    ru: messagesRu
  }
})
