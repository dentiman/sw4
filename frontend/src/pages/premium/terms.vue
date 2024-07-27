<template>
  <div>
    <v-sheet class="bg-sheet pb-0 mb-0" dark>
      <landing-menu></landing-menu>
      <v-container class="py-3 py-sm-3 text-center">
        <v-responsive max-width="800" class="mx-auto">
          <logo></logo>
          <h3 class="text-h4 text-sm-h4 my-4 text-white" style="color: white !important;">{{ $t('premium.terms') }}</h3>
        </v-responsive>
      </v-container>
    </v-sheet>
    <v-container class="py-1" style="margin-top: -50px" >
      <v-responsive max-width="900" class="mx-auto py-0">
        <v-card v-if="language === 'en'" >
          <v-card-text class="pt-3">
           <h4 class="my-1">SECTION 1 - ONLINE STORE TERMS</h4>

            <p>By agreeing to these Terms of Service, you represent that you are at least the age of majority in your state
              or province of residence, or that you are the age of majority in your state or province of residence and you
              have given us your consent to allow any of your minor dependents to use this site.</p>

            <p>You may not use our products for any illegal or unauthorized purpose nor may you, in the use of the Service,
              violate any laws in your jurisdiction (including but not limited to copyright laws).</p>

            <p>You must not transmit any worms or viruses or any code of a destructive nature.</p>

            <p>A breach or violation of any of the Terms will result in an immediate termination of your Services.</p>

            <p>We reserve the right to refuse service to anyone for any reason at any time.</p>

            <p>You understand that your content (not including credit card information), may be transferred unencrypted and
              involve (a) transmissions over various networks; and (b) changes to conform and adapt to technical
              requirements of connecting networks or devices. Credit card information is always encrypted during transfer
              over networks.</p>

            <p>You agree not to reproduce, duplicate, copy, sell, resell or exploit any portion of the Service, use of the
              Service, or access to the Service or any contact on the website through which the service is provided,
              without express written permission by us.</p>

            <p>The headings used in this agreement are included for convenience only and will not limit or otherwise affect
              these Terms.</p>

            <h4 class="my-1">SECTION 2 - ACCURACY, COMPLETENESS AND TIMELINESS OF INFORMATION</h4>

            <p>We are not responsible if information made available on this site is not accurate, complete or current. The
              material on this site is provided for general information only and should not be relied upon or used as the
              sole basis for making decisions without consulting primary, more accurate, more complete or more timely
              sources of information. Any reliance on the material on this site is at your own risk.</p>

            <h4 class="my-1">SECTION 3 - MODIFICATIONS TO THE SERVICE AND PRICES</h4>

            <p>Prices for our products are subject to change without notice.</p>

            <p>We reserve the right at any time to modify or discontinue the Service (or any part or content thereof)
              without notice at any time.</p>

            <p>We shall not be liable to you or to any third-party for any modification, price change, suspension or
              discontinuance of the Service.</p>

            <h4 class="my-1">SECTION 4 - SERVICES </h4>

            <p >All  services  available exclusively online through the website. These services may have limited quantities and are subject to return or exchange only according to our Return
              Policy.</p>

            <p>We reserve the right, but are not obligated, to limit the sales of our Services to any person,
              geographic region or jurisdiction. We may exercise this right on a case-by-case basis. We reserve the right
              to limit the quantities of any  services that we offer. All descriptions of Services or pricing are subject to change at anytime without notice, at the sole discretion of us. We reserve the right
              to discontinue any product at any time. Any offer for any product or service made on this site is void where
              prohibited.</p>

            <p>We do not warrant that the quality of any products, services, information, or other material purchased or
              obtained by you will meet your expectations, or that any errors in the Service will be corrected.</p>

           <h4 class="my-1">SECTION 5 - PERSONAL INFORMATION</h4>

            <p>Your submission of personal information through the store is governed by our <router-link to="/policy">Privacy Policy.</router-link> </p>

          <h4>SECTION 11 - ERRORS, INACCURACIES AND OMISSIONS</h4>

            <p>Occasionally there may be information on our site or in the Service that contains typographical errors,
              inaccuracies or omissions that may relate to product descriptions, pricing. We reserve the right to correct any errors, inaccuracies
              or omissions, and to change or update information or cancel orders if any information in the Service or on
              any related website is inaccurate at any time without prior notice (including after you have submitted your
              order).</p>

           <h4 class="my-1">SECTION 12 - PROHIBITED USES</h4>

            <p>In addition to other prohibitions as set forth in the Terms of Service, you are prohibited from using the
              site or its content:
              (a) for any unlawful purpose;<br>
              (b) to solicit others to perform or participate in any
              unlawful acts; <br>
              (c) to violate any international, federal, provincial or state regulations, rules, laws, or
              local ordinances; <br>
              (d) to infringe upon or violate our intellectual property rights or the intellectual
              property rights of others;<br>
              (e) to harass, abuse, insult, harm, defame, slander, disparage, intimidate, or
              discriminate based on gender, sexual orientation, religion, ethnicity, race, age, national origin, or
              disability; <br>
              (f) to submit false or misleading information; <br>
              (g) to upload or transmit viruses or any other
              type of malicious code that will or may be used in any way that will affect the functionality or operation
              of the Service or of any related website, other websites, or the Internet; <br>
              (h) to collect or track the personal information of others; <br>
              (i) to spam, phish, pharm, pretext, spider, crawl, or scrape; <br>
              (j) for any obscene or immoral purpose; or <br>
              (k) to interfere with or circumvent the security features of the Service or
              any related website, other websites, or the Internet. We reserve the right to terminate your use of the
              Service or any related website for violating any of the prohibited uses.</p>

           <h4 class="my-1">SECTION 13 - DISCLAIMER OF WARRANTIES; LIMITATION OF LIABILITY</h4>

            <p>We do not guarantee, represent or warrant that your use of our service will be uninterrupted, timely, secure
              or error-free.</p>

            <p>We do not warrant that the results that may be obtained from the use of the service will be accurate or
              reliable.</p>

            <p>You agree that from time to time we may remove the service for indefinite periods of time or cancel the
              service at any time, without notice to you.</p>

            <p>You expressly agree that your use of, or inability to use, the service is at your sole risk. The service and
              all products and services delivered to you through the service are (except as expressly stated by us)
              provided 'as is' and 'as available' for your use, without any representation, warranties or conditions of
              any kind, either express or implied, including all implied warranties or conditions of merchantability,
              merchantable quality, fitness for a particular purpose, durability, title, and non-infringement.</p>

            <h4 class="my-1">SECTION 19 - CHANGES TO TERMS OF SERVICE</h4>

            <p>You can review the most current version of the Terms of Service at any time at this page.</p>

            <p>We reserve the right, at our sole discretion, to update, change or replace any part of these Terms of
              Service by posting updates and changes to our website. It is your responsibility to check our website
              periodically for changes. Your continued use of or access to our website or the Service following the
              posting of any changes to these Terms of Service constitutes acceptance of those changes.</p>

            <h4 class="my-1">SECTION 20 - CONTACT INFORMATION</h4>

            <p>Questions about the Terms of Service should be sent to us at support@stock-watcher.com.</p>

          </v-card-text>
        </v-card>
        <v-card v-if="language === 'ru'" >
          <v-card-text>
            <h4 class="my-1">РАЗДЕЛ 1 - ОБЩИЕ УСЛОВИЯ</h4>
            <p>
            Настоящая оферта, является официальным предложением <b>ФОП Тимановский Денис Александрович</b>, далее по тексту — «Продавец»,
            заключить Договор купли-продажи услуг дистанционным способом, то есть через сайт Продавца «https://stock-watcher.com».</p>

            <p> Моментом полного и безусловного принятия Покупателем предложения Продавца (акцептом) заключить электронный договор купли-продажи
            услуг, считается факт оплаты Покупателем заказа на условиях настоящего Договора, в сроки и по ценам, указанным на сайте Продавца.</p>

         <h4 class="my-1">РАЗДЕЛ 2 - ПРЕДМЕТ ДОГОВОРА</h4>

            <p>Настоящий Договор регулирует куплю-продажу  Премиум аккаунта - закрытой части сайта, где размещается информация (графики, котировки) не доступная в Бесплатном аккаунте. </p>
            <p style="font-weight: bold">Продавец  предоставдяет исключительно информационные услуги. Все услуги (графики, котировки) доступны только в режиме онлайн через веб-сайт. Мы не предоставляем никаких брокерскийх, посредническийх или других финансовых услуг.</p>

            <p>Продавец обязуется обезпечить доступ к услуге через аккаунт Покупателя, а Покупатель обязуется оплатить и принять Услуги на условиях данного Договора.</p>

            <h4 class="my-1"> РАЗДЕЛ 3 - ПОРЯДОК ОПЛАТЫ ЗАКАЗА</h4>

            <p>Оплата производится на сайте Продавца за 1 месяц, 6 месяцев или 12 месяцев пользования Премиум аккаунтом. Сразу после успешной оплаты вы получаете все приемущества описаны на странице Премиум аккаунта</p>

          <h4 class="my-1">РАЗДЕЛ 4 -  ПРАВА И ОБЯЗАННОСТИ СТОРОН</h4>

            <p>Продавец имеет право:</p>

            <p>- в одностороннем порядке приостановить оказание услуг по настоящему договору в случае нарушения
              Покупателем условий настоящего договора.</p>

            <p>Покупатель обязан:</p>
            <p>- своевременно оплатить и получить заказ на условиях настоящего договора.</p>

            <p>Покупателю запрещено:</p>
            <p>- использовать какие-либо скрипты или вирусные программы в том числе и для копирования информации.</p>

            <p>- воспроизводить,дублировать, копировать, продавать, перепродавать или
              использовать какую-либо информацию с сайте, через который предоставляется услуга, без письменного
              разрешения от Продавца.</p>

            <p>Покупатель имеет право:</p>
            - оформить заказ онлайн на сайте Продавца <br>
            - требовать от Продавца исполнения условий настоящего Договора. <br>
            - требовать возврата денежных средств в случае несоблюдения даного Договора со стороны Продаца. <br><br>

            <h4 class="my-1">РАЗДЕЛ 5 - ПОРЯДОК ВОЗВРАТА ДЕНЕЖНЫХ СТРЕДСТВ</h4>
            <p>Возврат денежных средств производится согласно действующего законодательства Украины в случае если Покупатель не может получить досут к
            информации сайта (графики, котировки) по вине Продавца за исключением временных сбоев указаных в разделе 6 данного договора.</p>

            <h4 class="my-1">РАЗДЕЛ 5 - ЛИЧНАЯ ИНФОРМАЦИЯ</h4>

            <p>Использование представлениой Вами личной информации регулируется нашей политикой
              конфиденциальности. Cсылка всегда доступна внизу главной страницы сайта.</p>

            <h4 class="my-1">РАЗДЕЛ 6 -  ОТВЕТСТВЕННОСТЬ СТОРОН</h4>
            <p>Стороны несут ответственность за невыполнение или ненадлежащее выполнение условий настоящего договора в порядке,
              предусмотренном этим договором и действующим законодательством Украины.</p>

            <p>Вся информация, размещённая на нашем сайте, в том числе котировки и графики имеют
              исключительно информативный характер. Продавец не несет никакой ответственности за результаты
              деятельности Покупателя на финансовых рынках.</p>

            <p>Продавец не гарантирет, что использование Покупателем нашего сервиса
              будет все время бесперебойным, своевременным, безопасным или без ошибок.</p>

            <p>Продавец не гарантирет, что  успехи Покупателя и результаты, которые могут быть получены от
              использования сервиса, будут точными и надежными.</p>

            <p>Покупатель соглашается, что время от времени он может потерять доступ к сервису или какого-либо
              его раздела на некоторый период времени. Продавец старается, однако не обязан, предворительно уведомлять пользователей о каких
              либо сбоях, появления неточностей или потери доступа к Сервису.</p>

            <h4 class="my-1">РАЗДЕЛ 9 - КОНТАКНАЯ ИНФОРМАЦИЯ</h4>

            <p>Вопросы об условиях предоставления услуг должны быть направлены нам по адресу
              support@stocks-watcher.com.</p>
            <v-divider class="my-2"></v-divider>
            <p>ФОП Тимановский Денис Александрович <br>
            Украниа, 66502, г.Одесса, ул. Дальницкая, 46. офис 710</p>
          </v-card-text>
        </v-card>
      </v-responsive>
    </v-container>
    <Footer/>
  </div>
</template>

<script>
import LandingMenu from '@/layouts/LandingMenu'
import Logo from '@/components/Logo'
import Footer from '@/layouts/Footer'
export default {
  name: 'Term',
  components: {
    Logo,
    LandingMenu,
    Footer
  },
  computed: {
    language() {
      return this.$i18n.locale
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
