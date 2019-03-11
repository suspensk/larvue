<template>
  <v-card hover>
    <v-card-text class="pa-0">
      <vue-editor
        :customModules="customModulesForEditor"
        :editorToolbar="customToolbar"
        :editorOptions="editorSettings"
        v-model="content"
      >
      </vue-editor>
    </v-card-text>
    <v-card-actions v-if="addingProcess" >
      <v-progress-linear :indeterminate="true"></v-progress-linear>
    </v-card-actions>
    <v-card-actions>
      <v-btn icon> <v-icon>attach_file</v-icon> </v-btn>
      <v-btn icon> <v-icon>link</v-icon> </v-btn>
      <v-btn icon>
        <div class="upload-btn-wrapper">
          <v-icon>camera</v-icon>
          <input id="fileinput" type="file" name="myfile" @change="previewImage" accept="image/*" />
        </div>
         </v-btn>
      <template v-if="privacy == 0">
        <v-btn @click="privacy = 2" icon> <v-icon>public</v-icon></v-btn> public
      </template>
      <template v-else>
        <v-btn @click="privacy = 0" icon> <v-icon>lock</v-icon></v-btn> private
      </template>
      <v-spacer></v-spacer>
      <v-btn flat icon @click="handleSavingContent">
        <v-icon>send</v-icon>
      </v-btn>
    </v-card-actions>
    <div>
      <div class="file-upload-form">
       <!-- <input id="fileinput" type="file" @change="previewImage" accept="image/*">
-->


      </div>
      <div class="image-preview" v-if="imageData.length > 0">
        <v-btn @click="removeImage" icon> <v-icon>clear</v-icon></v-btn>
        <v-btn @click="rotateImage" icon> <v-icon>lock</v-icon></v-btn>
        <img id="rotating" class="preview" :src="imageData">
      </div>
    </div>
  </v-card>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { ImageDrop } from "quill-image-drop-module";
import ImageResize from "quill-image-resize-module";
import "quill-mention";
import NotesService from "@/services/notes";
import TagsService from "@/services/tags";

const atValues = [
  {
    id: "5a97b2a402de91c5b6c3e8a4",
    value: "Josie Rice",
    link: "http://www.josierice.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a464a8ff2d0996d2ef",
    value: "Elva Bowman",
    link: "mailto:elva@bowman.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4ecb768a2092a298b",
    value: "Ella Cochran",
    link: "http://www.ellacochran.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a418b984d2aff97657",
    value: "Knowles Walls",
    link: "mailto:knowles@walls.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad0",
    value: "Hanson Webb",
    link: "http://www.hansonwebb.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad1",
    value: "Maria Cruz",
    link: "mailto:maria@cruz.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad2",
    value: "Pablo Escobar",
    link: "http://www.pabloescobar.com",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad3",
    value: "Richard Smith",
    link: "mailto:richard@smith.com",
    myCustomProperty: "custom value"
  }
];

export default {
  name: "NewNote",
  components: {
    VueEditor
  },
  props: ["mode", "noteId", "noteText"],
  computed: {
    editorSettings() {
      var that = this;
      return {
        modules: {
          imageDrop: true,
          imageResize: {},
          mention: {
            allowedChars: /^[A-Za-zÅÄÖåäöА-Яа-я_]*$/,
            mentionDenotationChars: ["@", "#"],
            dataAttributes: ["myTagId"],
            source: function(searchTerm, renderList, mentionChar) {
              let values;
              if (mentionChar === "@") {
                values = atValues;
              } else {
                let tags = that.$store.getters.tags;
                values = tags.map((a) => {return { 'id' : a.id, 'value' : a.name, 'myTagId': a.id}});
                console.log('VALUES', values)
              }
              if (searchTerm.length === 0) {
                renderList(values.slice(0, 8), searchTerm);
              } else {
                let matches = values.filter(tag =>
                        tag.value.startsWith(searchTerm)
                );
                if(matches.length ==0){
                  matches = [{'id':0,'myTagId':0,'value':searchTerm}];
                }
                matches.sort(function(a, b){
                  return a.value.length - b.value.length;
                });

                renderList(matches.slice(0, 8), searchTerm);
              }
            }
          }
        }
      }
    }
  },
  data() {
    return {
      addingProcess: false,
      imageData: "",
      imageRemoved: false,
      imageFile: {},
      content: this.noteText != undefined ? this.noteText : "",
      tags: [],
      privacy: 0,
      customToolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["code-block"]
      ],
      customModulesForEditor: [
        { alias: "imageDrop", module: ImageDrop },
        { alias: "imageResize", module: ImageResize }
      ],

    };
  },
  methods: {
    async handleSavingContent() {
        var fd = new FormData();
      if(this.imageFile !== {}){
        fd.append('image', this.imageFile);
      }
        if(this.imageRemoved){
          fd.append('imageRemoved', true);
        }
          var htmlObject = document.createElement('div');
          htmlObject.innerHTML = this.content;
          let tagsList = htmlObject.querySelectorAll(".mention");
          let tagsData = [];
          let tagsDataNew = [];
          tagsList.forEach(
              function(tag) {
                  if(tag.dataset.id != 0){
                      tagsData.push(tag.dataset.id);
                  } else {
                      tagsDataNew.push(tag.dataset.value);
                  }
              }
          );

        fd.append('text', this.content);
        fd.append('privacy', this.privacy);
        fd.append('tags', JSON.stringify(tagsData));
        fd.append('newtags', JSON.stringify(tagsDataNew));

    //     NotesService.add(this.content).then(resp => {}).catch(err => {}); the same
      this.addingProcess = true;
        try {
          if(this.mode == "edit" && this.noteId != undefined){
            fd.append('id',this.noteId);
            const res = await NotesService.update(fd, this.noteId);
            this.$emit('close-editor', res);
            this.$radio.$emit('show-notice', 'primary', 'Note saved successfully');
          } else {
            const res = await NotesService.add(fd);
            this.$emit('reload-list');
            this.$radio.$emit('show-notice', 'primary', 'Note added successfully');
          }

        //  this.content = "";
          Object.assign(this.$data, this.$options.data())
        //  this.removeImage();
          this.$store
                  .dispatch("TAGS_REQUEST")
                  .catch((e) => {
                    this.$radio.$emit('show-notice', 'red', e);
                  });
          this.addingProcess = false;

        } catch (e) {
          this.$radio.$emit('show-notice', 'red', e);
          this.addingProcess = false;
        }
    },
    previewImage: function(event) {
      // Reference to the DOM input element
      var input = event.target;
      // Ensure that you have a file before attempting to read it
      if (input.files && input.files[0]) {
        this.imageFile = input.files[0];
        // create a new FileReader to read this image and convert to base64 format
        var reader = new FileReader();
        // Define a callback function to run, when FileReader finishes its job
        reader.onload = (e) => {
          // Note: arrow function used here, so that "this.imageData" refers to the imageData of Vue component
          // Read image as base64 and set to imageData
          this.imageData = e.target.result;
        }
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsDataURL(input.files[0]);
      }
    },
    removeImage: function(event){
      this.imageData = "";
      this.imageRemoved = true;
      this.imageFile = {};
      document.querySelector("#fileinput").value="";
    },
    rotateImage: function(event){
//      var offsetHeight = document.getElementById('rotating').offsetHeight;
//      var offsetWidth = document.getElementById('rotating').offsetWidth;
//      console.log(offsetHeight, offsetWidth)
    //  $('#rotating').toggleClass('rotated');
      var element = document.getElementById("rotating");
      element.classList.toggle("rotated");
      console.log(element.classList.contains("rotated"))

      var imgH = document.querySelector('.preview').offsetHeight;
      var imgW = document.querySelector('.preview').offsetWidth;
      console.log(imgH, imgW)
      var prH = document.querySelector('.image-preview').offsetHeight;
      var prW = document.querySelector('.image-preview').offsetWidth;
      console.log(prH, prW)

      if(element.classList.contains("rotated")){
        document.querySelector('.image-preview').style.height = imgW+100+"px";
      } else {
        document.querySelector('.image-preview').style.height = imgH+"px";
      }
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus">
 /* img {
    transform: rotate(180deg);
  }*/
 #rotating {

   /*width: 100px; height: 100px; background-color: Red; margin-top: 50px;
   -webkit-transition: all 0.3s ease-in-out;
   -moz-transition: all 0.3s ease-in-out;
   -o-transition: all 0.3s ease-in-out;
   transition: all 0.3s ease-in-out;*/
 }
 .rotated {
   transform:rotate(90deg);
   -webkit-transform:rotate(90deg);
   -moz-transform:rotate(90deg);
   -o-transform:rotate(90deg);
 }
  .file-upload-form, .image-preview {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  /*  padding: 20px;*/
  }
  img.preview {
    max-width: 200px;
  /*  max-height: 200px;*/
    background-color: white;
    border: 1px solid #DDD;
    padding: 5px;
   /* position: absolute;
    bottom: 40px;*/
  }

  .upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
  }

  .upload-btn-wrapper input[type=file] {
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
  }

</style>
