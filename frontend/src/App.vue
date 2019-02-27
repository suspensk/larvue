<template>
  <v-app>
    <v-snackbar
            v-model="snackbar"
            :multi-line="true"
            :right="true"
            :timeout="3000"
            :top="true"
            :color="snacbarColor"
    >
      {{snackbarText}}
    </v-snackbar>
    <app-toolbar></app-toolbar>
    <app-sidebar></app-sidebar>
    <v-content @show-notice="showNotice">
      <transition> <router-view></router-view> </transition>
    </v-content>
  </v-app>
</template>

<script>
import AppToolbar from "./components/Toolbar";
import AppSidebar from "./components/Sidebar";

export default {
  name: "App",
  components: {
    AppToolbar,
    AppSidebar
  },
  data() {
    return {
      snackbar: false,
      snackbarText : "test notice",
      snacbarColor: "primary"
      //
    };
  },
  created() {
    this.$radio.$on("show-notice", (type,text) => {
      this.showNotice(type,text);
  })

  this.$store
          .dispatch("TAGS_REQUEST")
          .catch((e) => {
            this.show-notice("red",e)
          });
  },

  methods: {
    logout() {},
    handleDrawerToggle() {},
    handleFullScreen() {},
    showNotice(type,text){
      this.snackbarText = text;
      this.snacbarColor = type;
      this.snackbar = true;
    }
  }
};
</script>
