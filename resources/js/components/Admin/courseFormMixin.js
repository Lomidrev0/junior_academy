import {i18n} from "../../app";

export default {
  methods: {

    onLogoSelected(event) {
      this.clearLogo();
      this.files.logo = event.target.files[0];
      var files = !!this.files ? this.files : [];
      if (/^image/.test( files.logo.type)){ // only image file
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files.logo); // read the local file
        this.setImg(reader,null);
      }
    },

    onBgImgSelected(event) {
      this.clearBgImg();
      this.files.bgImg = event.target.files[0];
      var files = !!this.files ? this.files : [];
      if (/^image/.test( files.bgImg.type)){ // only image file
        var reader = new FileReader(); // instance of the FileReader
        reader.readAsDataURL(files.bgImg); // read the local file
        this.setImg(null, reader);
      }
    },

    getBgImgName() {
      if (this.files.bgImg !== null){
        return this.files.bgImg.name;
      }
      else {
        return i18n('Choose image');
      }
    },

    getLogoName() {
      if (this.files.logo !== null){
        return this.files.logo.name;
      }
      else {
        return i18n('Choose image');
      }
    },

    clearLogo() {
      this.files.logo = null;
      $(".dynamic-logo").remove();
      $('.add-logo-icon').css('display','block')
    },

    clearBgImg() {
      this.files.bgImg = null;
      $(".dynamic-bg").remove();
      $('.add-bg-img-icon').css('display','block')
    },

    setImg(logo,bgImg) {
      if(logo !== null && bgImg === null) {
          logo.onload = function(){
            var l = $('<img class="dynamic-logo">');
            l.attr('src', this.result);
            l.appendTo($('#logo'));
            $('.add-logo-icon').css('display','none');
          }
      }
      else if(logo === null && bgImg !== null) {
          bgImg.onload = function(){
            var bg = $('<img class="dynamic-bg">');
            bg.attr('src', this.result);
            bg.appendTo($('#bgImg'));
            $('.add-bg-img-icon').css('display','none');
          }
      }
      else {
        var l = $('<img class="dynamic-logo">');
        l.attr('src', logo);
        l.appendTo($('#logo'));
        $('.add-logo-icon').css('display','none');
        var bg = $('<img class="dynamic-bg">');
        bg.attr('src', bgImg);
        bg.appendTo($('#bgImg'));
        $('.add-bg-img-icon').css('display','none');
      }
    },
    removeHTML(str){
      var tmp = document.createElement("DIV");
      tmp.innerHTML = str;
      return tmp.textContent || tmp.innerText || "";
    },
  }
}