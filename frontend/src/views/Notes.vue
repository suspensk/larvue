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
          <tags-filter  :feed="feed"></tags-filter>
        <new-note ref="newNote"  @push-note="pushNote" v-if="$store.getters.isAuthenticated" :mode="'add'"></new-note>


          <div v-for="post in notes" :key="post.id">
          <note @show-modal="dialog = true; curNote = post" @edit-note="editNote(); curNote = post" :post="post" :feed="feed"></note>
          </div>
          <div class="mb-5 text-xs-center">
              <v-btn v-if="!allLoaded && notesLoaded" @click="init()" color=primary>Load More</v-btn>
          </div>

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
      searchText:'',
      dialog: false,
      curNote: 0,
      notesLoaded:false,
    //  busy:false,
      page:1,
      allLoaded:false

    };
  },

  async created() {
    this.$radio.$on("TOGGLE", () => {
      this.drawer = !this.drawer;
    });

//      this.$radio.$on('search', (text) => {
//          this.searchText = text;
//          this.page= 1;
//
//          console.log('TEXT',text)
//          this.init();
//      });
//        var that = this;
//          window.onscroll = function() {
//          var pageY = window.pageYOffset || document.documentElement.scrollTop;
//          var innerHeight = document.documentElement.clientHeight;
//              console.log('csroll, allloaded = ', that.allLoaded);
//          if(pageY + innerHeight + 10 >= document.documentElement.scrollHeight && !that.busy && !that.allLoaded){
//              that.init(true);
//          }
//      };
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
       /* if(this.busy ==true){
            return;
        }
        this.busy =true;
        console.log(scroll,'scrolll')
        if(!scroll){
            this.notes = [];
            this.notesLoaded=false;
            this.busy=false;
            this.page=1;
            this.allLoaded=false;
        }*/

        this.$store.dispatch("COUNT_REQUEST");
        this.$store.dispatch("FEED_REQUEST", this.feed);
    //    console.log('notes disp',this.feed)
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
        if(this.searchText == "" && this.$route.query.search !== undefined ){
            this.searchText = this.$route.query.search;
        }
      try {
          const res = await NotesService.all(this.tags_ids, this.searchText, this.feed, this.page);

       //   this.notes = res.data;



          this.notes = res.data.reduce( function(coll,item){
              coll.push( item );
              return coll;
          }, this.notes );
          this.notesLoaded = true;
          if(res.next_page_url != null){
              this.page = ++res.current_page;
          } else {
              this.allLoaded = true;
          }
       //   this.busy = false;
      }  catch (e) {
          this.$radio.$emit('show-notice', 'red', e);
       //   this.busy = false;
      }
    },

      async deleteNote() {
          let note_id = this.curNote.id;
          try {
              await NotesService.delete(note_id);
              this.notes = this.notes.filter(function (note){
                  return note.id != note_id
              })
              this.$radio.$emit('show-notice', 'primary', 'Note successfully deleted');
            //  this.init();
          } catch(e) {
              this.$radio.$emit('show-notice', 'red', 'Error while deleting note');
          }
      },
      pushNote(note){
        this.notes.unshift(note);
      }
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
