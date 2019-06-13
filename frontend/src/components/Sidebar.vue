<template>
  <v-navigation-drawer
    :app="$vuetify.breakpoint.smAndDown"
    fixed
    clipped
    width="240"
    v-model="drawer"
    mobile-break-point="960"
    :class="{ menu: true, transparent: $vuetify.breakpoint.mdAndUp }"
  >
    <v-list class="mail-list" dense>
      <template  v-for="item in menus">
        <template v-if="item.visible">
        <v-layout row v-if="item.heading " align-center :key="item.heading">
          <v-flex xs12>
            <v-subheader v-if="item.heading"> {{ item.heading }} </v-subheader>
            <v-divider></v-divider>
          </v-flex>
        </v-layout>
        <!-- Top level !! -->
        <v-list-tile v-else :key="item.text" :to="item.to">
          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
          <v-chip
            class="caption red lighten-2 white--text chip--x-small mx-0"
            v-if="item.chip"
            label="label"
            small
            >{{ item.chip }}</v-chip
          >
        </v-list-tile>
        </template>
      </template>
    <!--  <v-layout row  align-center>
        <v-flex xs12>
          <v-divider></v-divider>
        </v-flex>
      </v-layout>-->
    </v-list>
    <v-sheet class="ml-1 mr-1" color="transparent" height="400" tag="div">
        <v-form  v-on:submit.prevent="search">
      <v-text-field
              v-model="searchText"
              label="Search"
              :rules="searchRules"
              :counter="10"
              required
      ></v-text-field>
        </v-form>
      <tag-cloud v-if="showCloud"></tag-cloud>
    </v-sheet>
  </v-navigation-drawer>
</template>

<script>
import TagCloud from "./TagCloud";

export default {
  name: "AppSidebar",
  components: {
    TagCloud
  },
  computed: {
      mainUrl(){
          return (this.feed == true ? 'feed' : 'notes');
      },
    feed() {
      return this.$store.getters.feed;
    },
    menus() {
      return [
        {
          title: "Feed",
          group: "notes",
          icon: "email",
          to: { path: "/feed" },
          chip: this.$store.getters.fCount,
          visible: true
        },
        {
          title: "My Notes",
          group: "notes",
          icon: "start",
          to: { path: "/notes" },
          chip: this.$store.getters.nCount,
          visible: this.$store.getters.isAuthenticated
        },
        {
          title: "Login",
          to: { path: "/login" },
          visible: !this.$store.getters.isAuthenticated
        },
        {
          title: "Register",
          to: { path: "/register" },
          visible: !this.$store.getters.isAuthenticated
        },
        {
          title: "Profile",
          to: { path: "/profile" },
          visible: this.$store.getters.isAuthenticated
        },
        /*{
          title: "Logout",
          to: { path: "/logout" },
          visible: this.$store.getters.isAuthenticated,
        },*/
      ];
    },
    showCloud() {
      return this.$route.path == "/feed" || this.$route.path == "/notes" || this.$route.path == "/";
    }
  },
  data() {
    return {
      searchText: '',
      searchRules: [
      /*  v => !!v || "E-mail is required",
      v => /.+@.+/.test(v) || "E-mail must be valid"*/
    ],
      drawer: !this.$vuetify.breakpoint.smAndDown
    };
  },

  created() {
    this.$radio.$on("TOGGLE", () => {
      this.drawer = !this.drawer;
    });
    if(this.$route.query.search !=undefined){
      this.searchText = this.$route.query.search;
    }
  },
  watch: {
    '$route' () {
     // Object.assign(this.$data, this.$options.data())
      this.searchText = "";
      if(this.$route.query.search !=undefined){
        this.searchText = this.$route.query.search;
      }
    }
  },
  methods: {
      search(){
          this.$router.push(this.createQuery());
      //    this.$radio.$emit('search',this.searchText);

      },
      createQuery(){
        let tags = "";
        let search = "";
        if(this.$route.query.tags !== undefined){
          tags = "?tags=" + this.$route.query.tags;
          search= "&search=";
        } else {
          search= "?search=";
        }
          if(this.searchText == ""){
              return '/' + this.mainUrl + tags;
          }
          let query = '/' + this.mainUrl + tags + search + this.searchText;
          return query;
      },
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus">
@import '~vuetify/src/stylus/main'
@media (min-width: $grid-breakpoints.md)
    .menu {
        top: 68px;
        right: 75%;
        left: auto;
    }
</style>
