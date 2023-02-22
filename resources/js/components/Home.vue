<template>
  <div>
    <div class="motd">
      <div>
        <h3 class="my-2">{{motd}} {{separateTitle(user.name)}}</h3>
        <p class="mx-2">Vitajte späť v systéme JUNIOR Akadémia.</p>
      </div>
      <div v-if="user.role === 0" class="info-ico-wr shadow">
        <i @click="openModal = true" class="bi bi-info-circle-fill"></i>
      </div>
    </div>
    <info-modal v-if="openModal && user.role === 0" @close="openModal = false">
              <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                   <div>
                     <h4>Základne informácie:</h4>
                     <br>
                     <p>Momentálne sa nachádzate na paneli žiaka, na ľavej strane obrazovky sa nachádza navigácia cez ktorú je možne prechádzať na vybraté podstránky.</p>
                     <br>
                     <h4>Informácie o účte:</h4>
                     <br>
                     <p>Účet na ktorom ste momentálne prihlásený je možné využívať iba počas školského roka. Po ukončení školského roka Vaše užívateľské konto  bude zmazané administrátorom. Ak budete mať záujem o kurzy aj ďalší školský rok je potrebné sa prihlásiť v ďalšom školskom roku nanovo po spustení registrácie, alebo sa odhlásiť. </p>
                     <br>
                   </div>
                  </div>
                  <div class="carousel-item">
                    <h4>Rozšírené informácie:</h4>
                    <br>
                    <img src="/images/student-home-1.png" class="d-block w-100" alt="#">
                    <br>
                    <p>1. Názov podstránky</p>
                    <p>2. Uvítanie použivateľa</p>
                    <p>3. Tlačidlo pre viac informácií</p>
                    <p>4. Prehľad kurzov na ktoré ste zaregistrovaný, po klinkutí na kartičku kurzu budete presmerovaný na detail kurzu</p>
                  </div>
                  <div class="carousel-item">
                    <h4>Rozšírené informácie:</h4>
                    <br>
                    <img src="/images/student-inbox-2.png" class="d-block w-100" alt="#">
                    <br>
                    <p>1. Názov podstránky</p>
                    <p>2. Informačná karta</p>
                    <p>3. Tlačidlo pre napísanie správy</p>
                    <p>4. Zoznam všetkých správ zoradených podľa dátumu po kliknutí na správu sa zobrazí detail správy</p>
                  </div>
                  <div class="carousel-item">
                    <h4>Rozšírené informácie:</h4>
                    <br>
                    <img src="/images/student-password-3.png" class="d-block w-100" alt="#">
                    <br>
                    <p>1. Názov podstránky</p>
                    <p>2. Informačná karta</p>
                    <p>3. Formulár pre zmenu hesla.</p>
                  </div>
                </div>
              </div>
    </info-modal>
  </div>
</template>

<script>
import InfoModal from "./InfoModal";
import {i18n} from "../app";
export default {
  components: {InfoModal},
  props: ['user'],
  data() {
    return {
      openModal: false,
      motd: '',
    }
  },
  methods: {
    showTimeMsg() {
      var today = new Date()
      var curHr = today.getHours()

      if (curHr < 12) {
        this.motd = i18n('Good morning')
      } else if (curHr < 18) {
        this.motd = i18n('Good afternoon')
      } else {
        this.motd = i18n('Good evening')
      }
    },
    separateTitle(fullName) {
      return _.chain(fullName)
          .replace(/^[a-zA-Z]+\./, '.') // remove any title at the beginning of the string (e.g. "Ing.")
          .split(' ') // split the remaining string into separate words
          .filter(name => !_.endsWith(name, '.')) // filter out components that end with a period (i.e. titles)
          .first() // get the first remaining component
          .trim() // trim any leading or trailing whitespace
          .value(); // extract the final result from the chain
    }
  },
  mounted() {
    this.showTimeMsg();
    setInterval(function() {
      var now = new Date();
      if (now.getSeconds() === 0) {
        this.showTimeMsg;
      }
    }, 1000);
  }
}
</script>

<style scoped>

</style>