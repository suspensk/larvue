<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex lg6 md8 xs12 offset-md3> <note :note="note"></note> </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import Note from "@/components/Note";
import NotesService from "@/services/notes";

export default {
  name: "Notes",
  components: {
    Note
  },
  data() {
    return {
      note: {}
    };
  },

  async created() {
    this.$radio.$on("TOGGLE", () => {
      this.drawer = !this.drawer;
    });

    const res = await NotesService.one(this.$route.params.id);
    console.log(this.$route.params.id, res);
    this.note = res.note;
  },

  methods: {}
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
