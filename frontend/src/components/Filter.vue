<template>
  <v-card
          color="blue-grey darken-1"
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
  import { mapGetters } from 'vuex';

  export default {
    props: ["usedTags"],
    data () {
      return {
        autoUpdate: true,
        selectedTags: this.usedTags,
        isUpdating: false,
        name: 'Midnight Crew',
        title: 'The summer breeze'
      }
    },
    computed: {
      tags() {
        return this.$store.getters.tags;
      }
    },
    watch: {
      isUpdating (val) {
        if (val) {
          setTimeout(() => (this.isUpdating = false), 3000)
        }
      }
    },
    methods: {
      remove (item) {
        const index = this.selectedTags.indexOf(item.name)
        if (index >= 0) this.selectedTags.splice(index, 1)
      }
    }
  }
</script>