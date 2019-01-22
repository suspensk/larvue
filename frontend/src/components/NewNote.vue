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
    <v-card-actions>
      <v-btn icon> <v-icon>attach_file</v-icon> </v-btn>
      <v-btn icon> <v-icon>link</v-icon> </v-btn>
      <v-btn icon> <v-icon>camera</v-icon> </v-btn>
      <v-spacer></v-spacer>
      <v-btn flat icon @click="handleSavingContent">
        <v-icon>send</v-icon>
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import { VueEditor } from "vue2-editor";
import { ImageDrop } from "quill-image-drop-module";
import ImageResize from "quill-image-resize-module";
import "quill-mention";

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
const hashValues = [
  {
    id: "5a97b2a402de91c5b6c3e8a5",
    value: "josiePrice",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a464a8ff2d0996d2eg",
    value: "elvaShowman",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4ecb768a2092a298c",
    value: "ellaCoach",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a418b984d2aff97658",
    value: "knowlesWalis",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad1",
    value: "hansonWebster",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad2",
    value: "Maria Cruiser",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad3",
    value: "Pablo Escobeer",
    myCustomProperty: "custom value"
  },
  {
    id: "5a97b2a4436c2c9acc6b5ad4",
    value: "Richard Schmidt",
    myCustomProperty: "custom value"
  }
];

export default {
  name: "NewNote",
  components: {
    VueEditor
  },
  data() {
    return {
      content: "",
      customToolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["code-block"]
      ],
      customModulesForEditor: [
        { alias: "imageDrop", module: ImageDrop },
        { alias: "imageResize", module: ImageResize }
      ],
      editorSettings: {
        modules: {
          imageDrop: true,
          imageResize: {},
          mention: {
            allowedChars: /^[A-Za-z\sÅÄÖåäö]*$/,
            mentionDenotationChars: ["@", "#"],
            dataAttributes: ["myCustomProperty"],
            source: function(searchTerm, renderList, mentionChar) {
              let values;
              if (mentionChar === "@") {
                values = atValues;
              } else {
                values = hashValues;
              }
              if (searchTerm.length === 0) {
                renderList(values, searchTerm);
              } else {
                const matches = values.filter(tag =>
                  tag.value.startsWith(searchTerm)
                );
                renderList(matches, searchTerm);
              }
            }
          }
        }
      }
    };
  },

  created() {},

  methods: {
    handleSavingContent: function() {
      // You have the content to save
      console.log(this.content);
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus"></style>
