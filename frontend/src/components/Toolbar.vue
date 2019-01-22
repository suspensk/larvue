<template>
  <v-toolbar app dense>
    <v-toolbar-title class="headline text-uppercase">
      <span>Notes</span> <span class="font-weight-light">.me</span>
    </v-toolbar-title>
    <v-spacer></v-spacer>
    <v-menu
      offset-y
      origin="center center"
      :nudge-bottom="10"
      transition="scale-transition"
    >
      <v-btn icon large flat slot="activator">
        <v-avatar class="while" size="30px"> <span>AG</span> </v-avatar>
      </v-btn>
      <v-list class="pa-0">
        <v-list-tile ripple="ripple" rel="noopener" :to="{ name: 'settings' }">
          <v-list-tile-action> <v-icon>settings</v-icon> </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Settings</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="handleFullScreen" ripple="ripple" rel="noopener">
          <v-list-tile-action> <v-icon>fullscreen</v-icon> </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Fullscreen</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="logout" ripple="ripple" rel="noopener">
          <v-list-tile-action>
            <v-icon>fullscreen_exit</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Logout</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-menu>
    <v-toolbar-side-icon
      v-if="$vuetify.breakpoint.smAndDown"
      @click.stop="$radio.$emit('TOGGLE')"
    ></v-toolbar-side-icon>
  </v-toolbar>
</template>

<script>
export default {
  name: "app-toolbar",
  data() {
    return {};
  },
  methods: {
    logout() {},
    handleDrawerToggle() {},
    handleFullScreen() {}
  }
};
</script>
