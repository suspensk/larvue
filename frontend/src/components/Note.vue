<template>
  <v-card
    class="my-3"
    hover
    :to="{ name: 'single.note', params: { id: note.id } }"
  >
    <v-toolbar color="white" dense flat>
      <v-list-tile>
        <v-list-tile-avatar>
          <v-img
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-tile-avatar>

        <v-list-tile-content class="pl-0">
          <v-list-tile-title>{{ note.user.name }}</v-list-tile-title>
         <!-- <v-list-tile-sub-title v-if="note.privacy == 0">public</v-list-tile-sub-title>
          <v-list-tile-sub-title v-else-if="note.privacy == 2">private</v-list-tile-sub-title>-->
          <v-list-tile-sub-title>{{note.created_at}},
             <span v-if="note.privacy == 0">public</span>
            <span v-else-if="note.privacy == 2">private</span>
          </v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>

      <v-spacer></v-spacer>

      <v-btn icon> <v-icon>more_vert</v-icon> </v-btn>
    </v-toolbar>

    <template  v-if="note.images">
      <v-img v-for="image in note.images" :key="image.id" class="white--text" :src="'/uploads/540-' + image.name"> </v-img>
    </template>

    <v-img class="white--text" :src="note.image" v-if="note.image">
      <v-container fill-height fluid>
        <v-layout>
          <v-flex xs12 align-end d-flex>
            <span class="headline">{{ note.title }}</span>
          </v-flex>
        </v-layout>
      </v-container>
    </v-img>
    <v-card-title v-else primary-title>
      <span class="headline">{{ note.title }}</span>
    </v-card-title>
    <v-card-text v-html="note.text"></v-card-text>
      <v-card-title primary-title>
      <span class="grey--text">
      Used tags:
      <span v-for="tag,tagIndex in note.tags" :id="tag.id">
        <a v-bind:href="'/notes?tags='+ tag.name">#{{tag.name}}</a>&nbsp;&nbsp;
     </span></span>
      </v-card-title>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn  v-if="note.limited" @click.prevent="more()" flat class="blue--text">Read More</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
import NotesService from "@/services/notes";
export default {
  name: "Note",
  props: ["post"],
  data() {
    return {
      note: this.post
    };
  },
  computed: {
    /*show() {
      return this.note.privacy === 0;
    }*/
  },
  methods: {
    async more() {
     // e.preventDefault();
      let text = await NotesService.more(this.note.id);
      this.note.text = text;
      this.note.limited=false;
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus"></style>
