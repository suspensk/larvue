<template>
  <v-container fluid>
      <modal v-if="showModal" @close="showModal = false">
            <span slot="body">
                are you sure you want to delete?
            </span>
          <span slot="footer">
                         <button class="modal-default-button" @click="deleteNote()">
                          YES
                         </button>
                         <button class="modal-default-button" @click="showModal = false">
                          NO
                         </button>
                    </span>
          <h3 slot="header">Confirmation</h3>
      </modal>
    <v-layout row wrap>
      <v-flex xl4 lg5 md8 xs12 offset-md3>
        <new-note @reload-list="init()" v-if="$store.getters.isAuthenticated"></new-note>
        <div v-for="post in notes" :key="post.id">
          <note @reload-list="init()" @show-modal="showModal = true; curNote = post" :post="post"></note>
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
      showModal: false,
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
          this.showModal = false;
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
