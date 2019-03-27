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

        <new-note ref="newNote" @reload-list="init()" v-if="$store.getters.isAuthenticated" :mode="'add'"></new-note>
          <tags-filter :feed="feed"></tags-filter>
        <template v-if="!notesLoaded">
            <div class="text-xs-center">
                <v-flex my-5>
                    <v-progress-circular
                            :size="70"
                            color="primary"
                            indeterminate
                    ></v-progress-circular>
                  </v-flex>
            </div>
        </template>
          <div v-for="post in notes" :key="post.id">
          <note @show-modal="dialog = true; curNote = post" @edit-note="editNote(); curNote = post" :post="post"></note>
        </div>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import Note from "@/components/Note";
import NewNote from "@/components/NewNote";
import NotesService from "@/services/notes";
import TagsService from "@/services/tags";
import Modal from '@/components/ModalWindow';
import TagsFilter from '@/components/Filter';
export default {
  name: "Notes",
  components: {
    Note,
    NewNote,
    Modal,
    TagsFilter,
  },
    props: ["feed"],
  data() {
    return {
      notes: [],
      tags_ids:[],
      dialog: false,
      curNote: 0,
      notesLoaded:false,
    };
  },

  async created() {
    this.$radio.$on("TOGGLE", () => {
      this.drawer = !this.drawer;
    });
//      this.$radio.$on('tag-search', (tags) => {
//          this.tags_ids = tags;
//          this.init();
//      });

    this.init();
  },
    watch: {
        '$route' (to, from) {
            Object.assign(this.$data, this.$options.data())
            this.init();
        }
    },
  methods: {
    async init() {
        this.$store.dispatch("COUNT_REQUEST");
        this.$store.dispatch("FEED_REQUEST", this.feed);
        this.notesLoaded = false;
        if(this.tags_ids.length ==0 && this.$route.query.tags !== undefined ){
            let tags_names = this.$route.query.tags.split(",");
            try {
                let tagsObjs = await TagsService.all(tags_names);
                this.tags_ids = tagsObjs.map(a => a.id);
            } catch (e) {
                this.$radio.$emit('show-notice', 'red', 'Error tags loading');
            }
        }
      try {
          const res = await NotesService.all(this.tags_ids, this.feed);
          this.notes = res;
          this.notesLoaded = true;
      }  catch (e) {
          this.$radio.$emit('show-notice', 'red', e);
      }
    },

      async deleteNote() {
          let note_id = this.curNote.id;
          try {
              await NotesService.delete(note_id);
              this.$radio.$emit('show-notice', 'primary', 'Note successfully deleted');
              this.init();
          } catch(e) {
              this.$radio.$emit('show-notice', 'red', 'Error while deleting note');
          }
      },
      async editNote() {
       //   let note_id = this.curNote.id;
       //   this.$refs.newNote.content = this.curNote.text;
//          try {
//              await NotesService.delete(note_id);
//              this.$radio.$emit('show-notice', 'primary', 'Note successfully deleted');
//              this.init();
//          } catch(e) {
//              this.$radio.$emit('show-notice', 'red', 'Error while deleting note');
//          }
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
