<template>
  <div>
    <div class="motd">
      <div>
        <h5>Vitajte v systéme JUNIOR Akadémia.</h5>
        <h3>{{motd}}{{user.name}}</h3>
      </div>
        <i @click="openModal = true" class="bi bi-info-circle-fill"></i>
    </div>
    <info-modal v-if="openModal" @close="openModal = false">
              <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                   <div>
                     <p>Vitajte v systéme JUNIOR Akadémia.</p>
                     <p>Základne informácie:</p>
                     <p>Momentálne sa nachádzate na paneli žiaka, na ľavej strane obrazovky sa nachádza navigácia cez ktorú je možne prechádzať na vybraté podstránky.</p>
                     <p>Informácie o účte:</p>
                     <p>Účet na ktorom ste momentálne prihlásený je možné využívať iba počas školského roka. Po ukončení školského roka Vaše užívateľské konto  bude zmazané administrátorom. Ak budete mať záujem o kurzy aj ďalší školský rok je potrebné sa prihlásiť v ďalšom školskom roku nanovo po spustení registrácie, alebo sa odhlásiť. </p>
                   </div>
                  </div>
                  <div class="carousel-item">
                    <img src="https://img.freepik.com/free-photo/beautiful-shot-forest-with-tall-green-trees_181624-20615.jpg?w=2000" class="d-block w-100" alt="#">
                  </div>
                  <div class="carousel-item">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTqOzahsOHBbMi04ElGC3E6y4Wg4Vfe7Vjing&usqp=CAU" class="d-block w-100" alt="#">
                  </div>
                </div>
              </div>

<!--      <p>Vitajte v systéme JUNIOR Akadémia.</p>-->
<!--      <p>Základne informácie:</p>-->
<!--      <p>Momentálne sa nachádzate na paneli žiaka, na ľavej strane obrazovky sa nachádza navigácia cez ktorú je možne prechádzať na vybraté podstránky.</p>-->
<!--      <p>Informácie o účte:</p>-->
<!--      <p>Účet na ktorom ste momentálne prihlásený je možné využívať iba počas školského roka. Po ukončení školského roka Vaše užívateľské konto  bude zmazané administrátorom. Ak budete mať záujem o kurzy aj ďalší školský rok je potrebné sa prihlásiť v ďalšom školskom roku nanovo po spustení registrácie. </p>-->
<!--&lt;!&ndash;      <img src="/images/ezgif.com-video-to-gif.gif" alt="">&ndash;&gt;-->
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
        this.motd = i18n('good morning')
      } else if (curHr < 18) {
        this.motd = i18n('good afternoon')
      } else {
        this.motd = i18n('good evening')
      }
    },
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