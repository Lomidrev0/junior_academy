import {i18n} from "../app";

export default {
  methods: {

    onFileSelected(event,file,key) {
      file = this.clearFile(key);
      file = event.target.files[0];
      var reader = new FileReader(); // instance of the FileReader
      reader.readAsDataURL(file); // read the local file
      this.setImg(reader,key);
      return event.target.files[0];
    },

    clearFile(key) {
      $("#img-"+key).remove();
      $('#ico-'+key).css('display','block')
      return null;
    },

    setImg(img, key) {
      img.onload = function(){
        var l = $('<img class="dynamic">').attr('id','img-'+key);
        l.attr('src', this.result);
        l.appendTo($('#append-'+key));
        $('#ico-'+key).css('display','none');
      }
    },

    setExistingImg(imgs, keys) {
      _.forEach(_.map(imgs, (img)=>{ return img.original_url }), function(value,index) {
         var l = $('<img class="dynamic">').attr('id','img-'+keys[index]);
         l.attr('src', value);
         l.appendTo($('#append-'+keys[index]));
        $('#ico-'+keys[index]).css('display','none');
      });
    },

    removeHTML(str){
      var tmp = document.createElement("DIV");
      tmp.innerHTML = str;
      return tmp.textContent || tmp.innerText || "";
    },
  }
}