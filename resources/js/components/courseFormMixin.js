import {i18n} from "../app";

export default {
  methods: {

    onFileSelected(event,file,key) {
      file = this.clearFile(key);
      file = event.target.files[0];
      var reader = new FileReader(); // instance of the FileReader
      reader.readAsDataURL(file); // read the local file
      this.setImg(reader,key);
      return Array.from(event.target.files);

    },

    resetInput(key){
      document.getElementById('input-'+key).value = null;
    },

    clearFile(keys) {
     if(Array.isArray(keys)){
       _.forEach(keys, (key) =>{
         $("#img-"+key).remove();
         $('#ico-'+key).css('display','block')
       })
     }
     else {
       $("#img-"+keys).remove();
       $('#ico-'+keys).css('display','block')
     }
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
    getFileName(file) {
     if (file.length === 1){
       return file[0].name;
     }
     else {
       return file.length+' '+i18n('Files selected');
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