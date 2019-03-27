<template>
  <v-container fluid>
    <v-layout row wrap>
      <v-flex lg6 md8 xs12 offset-md3>
        <v-card hover>

          <v-card-media
                  src="https://raw.githubusercontent.com/ijklim/simon-game/gh-pages/assets/img/bg--game-pad.jpg"
                  height="150px"
          >
          </v-card-media>

          <v-card-title>
            <h2>Your profile</h2>
          </v-card-title>

          <v-card-text>
            <p>Name: {{name}}</p>
            <p>Email: {{email}}</p>
          </v-card-text>

          <v-card-actions>
            <v-btn color=success>Edit</v-btn>
           <!-- <v-btn flat color=primary>Click #2</v-btn>-->
            <v-spacer></v-spacer>
           <!-- <v-btn icon><v-icon>bookmark</v-icon></v-btn>-->
          </v-card-actions>

        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  components: {},
  computed: {
    name() {
      return this.$store.getters.name;
    },
    email() {
      return this.$store.getters.email;
    },
  },
  data() {
    return {};
  },

  async created() {},

  methods: {}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus"></style>
