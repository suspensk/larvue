<template>
  <v-card
          color="blue-grey darken-1"
          dark
  >
      <v-layout wrap>
          <v-flex xs12>
            <v-autocomplete
                    v-model="selectedTagsNames"
                    :disabled="isUpdating"
                    :items="tags"
                    box
                    chips
                    color="blue-grey lighten-2"
                    label="Select"
                    item-text="name"
                    item-value="name"
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

  export default {
    data () {
      return {
        autoUpdate: true,
        isUpdating: false,
        selectedTagsNames: [],
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
      }
    },
    methods: {
        init(){
            if(this.$route.query.tags !== undefined ){
                this.selectedTagsNames = this.$route.query.tags.split(",");

            }
        },
      remove (item) {
        const index = this.selectedTagsNames.indexOf(item.name)
        if (index >= 0) {
          this.selectedTagsNames.splice(index, 1);
       //   this.$radio.$emit('tag-search',this.selectedTags);
        }

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