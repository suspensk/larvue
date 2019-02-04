<template>
  <v-container fluid>
      <modal v-if="dialog" @close="dialog = false">
          <template slot="card">
              <v-card>
                  <v-card-title class="headline">Confirmation</v-card-title>
                  <v-card-text>Are you sure you want to delete?</v-card-text>
                  <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="green darken-1" flat @click="dialog = false">NO</v-btn>
                      <v-btn color="red darken-1" flat @click="dialog = false; deleteNote();">YES</v-btn>
                  </v-card-actions>
              </v-card>
          </template>
      </modal>
    <v-layout row wrap>
      <v-flex xl4 lg5 md8 xs12 offset-md3>
        <new-note @reload-list="init()" v-if="$store.getters.isAuthenticated"></new-note>
        <div v-for="post in notes" :key="post.id">
          <note @reload-list="init()" @show-modal="dialog = true; curNote = post" :post="post"></note>
        </div>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import Note from "@/components/Note";
import NewNote from "@/components/NewNote";
import NotesService from "@/services/notes";
import Modal from '@/components/ModalWindow';
export default {
  name: "Notes",
  components: {
    Note,
    NewNote,
    Modal
  },
  data() {
    return {
      notes: [],
      dialog: false,
      curNote: 0
    };
  },

  async created() {
    this.$radio.$on("TOGGLE", () => {
      this.drawer = !this.drawer;
    });

    this.init();
  },

  methods: {
    async init() {
      const res = await NotesService.all();
      this.notes = res;
    },

      async deleteNote() {
          let note_id = this.curNote.id;
          await NotesService.delete(note_id);
          this.init();
      },
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus">
h3
  margin 40px 0 0

ul
  list-style-type none
  padding 0

li
  display inline-block
  margin 0 10px

a
  color #42b983
</style>
