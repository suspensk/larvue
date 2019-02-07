<template>
  <v-card
    class="my-3"
    hover
  >
    <v-toolbar color="white" dense flat>
      <v-list-tile>
        <v-list-tile-avatar>
          <v-img
            src="https://avataaars.io/?avatarStyle=Transparent&topType=ShortHairShortCurly&accessoriesType=Prescription02&hairColor=Black&facialHairType=Blank&clotheType=Hoodie&clotheColor=White&eyeType=Default&eyebrowType=DefaultNatural&mouthType=Default&skinColor=Light"
          ></v-img>
        </v-list-tile-avatar>

        <v-list-tile-content class="pl-0">
          <v-list-tile-title>
              {{ note.user.name }}
              <span v-if="note.privacy == 0">
                   <v-icon>public</v-icon>
              </span>
              <span v-else>
                   <v-icon>lock</v-icon>
              </span>
          </v-list-tile-title>
         <!-- <v-list-tile-sub-title v-if="note.privacy == 0">public</v-list-tile-sub-title>
          <v-list-tile-sub-title v-else-if="note.privacy == 2">private</v-list-tile-sub-title>-->
          <v-list-tile-sub-title>
              <span>{{note.created_at | formatDate}}</span>
          </v-list-tile-sub-title>
        </v-list-tile-content>
      </v-list-tile>



      <v-spacer></v-spacer>
        <v-menu
                offset-y
                origin="center center"
                :nudge-bottom="10"
                transition="scale-transition"
        >
            <v-btn icon slot="activator"> <v-icon>more_vert</v-icon> </v-btn>

            <v-list class="pa-0">
                <v-list-tile ripple="ripple" rel="noopener" @click.stop="$emit('show-modal');">
                    <v-list-tile-action> <v-icon>remove_circle_outline </v-icon> </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Delete</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile ripple="ripple" rel="noopener" @click.stop="$emit('edit-note');">
                    <v-list-tile-action> <v-icon>edit</v-icon> </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Edit</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile ripple="ripple" rel="noopener">
                    <v-list-tile-action>
                        <v-icon>fullscreen_exit</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Logout</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-menu>

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
        <router-link :to="'/notes?tags='+ tag.name">#{{tag.name}}</router-link>&nbsp;&nbsp;
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
import moment from 'moment';

export default {
  name: "Note",
  props: ["post"],
  data() {
    return {
      note: this.post,

    };
  },
  computed: {
    /*show() {
      return this.note.privacy === 0;
    }*/
  },
    filters: {
        formatDate: function (value) {
            if (value) {
                return moment(String(value)).calendar();
            }
        }
    },
  methods: {
    async more() {
     // e.preventDefault();
      let text = await NotesService.more(this.note.id);
      this.note.text = text;
      this.note.limited=false;
    },

  },
    components: {
      //  Modal
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus"></style>
