<template>
  <v-card
          color="blue-grey darken-1"
          dark
  >
      <v-layout wrap>
          <v-flex xs12>
            <v-autocomplete
                    v-model="selectedTagsIds"
                    :disabled="isUpdating"
                    :items="tags"
                    box
                    chips
                    color="blue-grey lighten-2"
                    label="Select"
                    item-text="name"
                    item-value="id"
                    multiple
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
    import TagsService from "@/services/tags";
  export default {
    data () {
      return {
        autoUpdate: true,
        isUpdating: false,
        selectedTagsIds: [],
      }
    },
    created() {
          this.init();
    },
    computed: {
      tags() {
        return this.$store.getters.tags;
      },
    },
    watch: {
      isUpdating (val) {
        if (val) {
          setTimeout(() => (this.isUpdating = false), 3000)
        }
      },
        selectedTagsIds (val) {
            this.$radio.$emit('tag-search',val);
        }
    },
    methods: {
        async init(){
            if(this.$route.query.tags !== undefined ){
                let tags_names = this.$route.query.tags.split(",");
                let tagsObjs = await TagsService.all();
                const result = tagsObjs.filter(function(tag){
                    return tags_names.indexOf(tag.name) != -1
                });
                this.selectedTagsIds = result.map(a => a.id);
                console.log('rez',result)

            }
        },
      remove (item) {
        const index = this.selectedTagsIds.indexOf(item.id)
        if (index >= 0) {
          this.selectedTagsIds.splice(index, 1);
        }

      },
        createQuery(){
            if(this.tags.length == 0){
                return '/notes';
            }
            let tags_names = this.tags.map(a => a.name);
            let tags_string = tags_names.join();
            let query = '/notes?tags=' + tags_string;
            return query;
        },

//        setTag (obj) {
//            this.tags.push(obj);
//            this.$refs.autocomplete.setValue('');
//            this.$router.push(this.createQuery());
//            this.$radio.$emit('tag-search',this.tags);
//        },
    }
  }
</script>