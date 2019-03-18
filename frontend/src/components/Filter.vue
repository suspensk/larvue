<template>
  <v-card
          color="red lighten-2"
          dark
  >
      <v-layout wrap>
          <v-flex xs12>
            <v-autocomplete
                    v-model="selectedTags"
                    :disabled="isUpdating"
                    :items="tags"
                    box
                    chips
                    color="blue-grey lighten-2"
                    label="Select tags to filter notes"
                    item-text="name"
                    item-value="id"
                    multiple
                    :return-object="true"
                    @input="input"
            >
              <template
                      slot="selection"
                      slot-scope="data"
              >
                <v-chip
                        :selected="data.selected"
                        close
                        class="chip--select-multi"
                        @input="remove(data.item)"
                >
                  {{ data.item.name }}
                </v-chip>
              </template>
              <template
                      slot="item"
                      slot-scope="data"
              >
                <template v-if="typeof data.item !== 'object'">
                  <v-list-tile-content v-text="data.item"></v-list-tile-content>
                </template>
                <template v-else>
                  <v-list-tile-content>
                    <v-list-tile-title v-html="data.item.name"></v-list-tile-title>
                    <v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>
                  </v-list-tile-content>
                </template>
              </template>
            </v-autocomplete>
          </v-flex>
        </v-layout>
  </v-card>
</template>
<script>

  export default {
    props: ["feed"],
    data () {
      return {
        autoUpdate: true,
        isUpdating: false,
        selectedTags: [],
      }
    },
    created() {
      this.$radio.$on('tags-loaded', () => {
        this.init();
      });
    },
    computed: {
      tags() {
          const tags = this.$store.getters.tags;
          return tags;
      },
      mainUrl(){
          return (this.feed == true ? 'feed' : 'notes');
      }
    },
    watch: {
//      isUpdating (val) {
//        if (val) {
//          setTimeout(() => (this.isUpdating = false), 3000)
//        }
//      },
        '$route' (to, from) {
            Object.assign(this.$data, this.$options.data())
            this.init();
        }
    },
    methods: {
        async init(){
            if(this.$route.query.tags !== undefined){
                let queryTagsNames = this.$route.query.tags.split(",");
                let filterTagsNames =  this.selectedTags.map(a => a.name);

                Array.prototype.diff = function (a) {
                    return this.filter(function (i) {
                        return a.indexOf(i) === -1;
                    });
                };
                let diff = queryTagsNames.diff(filterTagsNames);
                if(diff.length){
                    let queryTags = this.tags.filter(function(tag){
                        return queryTagsNames.indexOf(tag.name) != -1
                    });
                    this.selectedTags = queryTags;
                }
            }
        },
      remove (item) {
            this.selectedTags = this.selectedTags.filter(function(tag){
                return tag.id != item.id
            });
          this.$router.push(this.createQuery());
      },
        input (val) {
            this.$router.push(this.createQuery());
        },
        createQuery(){
            let val = this.selectedTags;
            if(val.length == 0){
                return '/' + this.mainUrl;
            }
            let tags_names = val.map(a => a.name);
            let query = '/' + this.mainUrl + '?tags=' + tags_names;
            return query;
        },
    }
  }
</script>