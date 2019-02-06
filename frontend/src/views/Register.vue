<template>
  <v-container fluid fill-height>
    <v-snackbar
            v-model="snackbar"
            :multi-line="false"
            :right="true"
            :timeout="3000"
            :top="true"
            :color="snacbarColor"
    >
      {{snackbarText}}
    </v-snackbar>
    <v-layout align-center justify-center>
      <v-flex xs12 sm8 md4 lg4>
        <v-card class="elevation-1 pa-3">
          <v-card-text>
            <div class="layout column align-center">
              <h1 class="flex my-4 primary--text">
                <span>Notes</span> <span class="font-weight-light">.me</span>
              </h1>
            </div>
            <v-form>
              <v-text-field
                      append-icon="person"
                      name="name"
                      label="Name"
                      type="text"
                      v-model="model.username"
              ></v-text-field>
              <v-text-field
                append-icon="email"
                name="email"
                label="Email"
                type="text"
                v-model="model.email"
              ></v-text-field>
              <v-text-field
                append-icon="lock"
                name="password"
                label="Password"
                id="password"
                type="password"
                v-model="model.password"
              ></v-text-field>
              <v-text-field
                      append-icon="check"
                      name="password_confirmation"
                      label="Confirm"
                      id="password_confirmation"
                      type="password"
                      v-model="model.password_confirmation"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn block color="primary" @click="register" :loading="loading"
              >Register</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import NotesService from "@/services/notes";
export default {
  data: () => ({
    loading: false,
    snackbar: false,
    snackbarText : "test notice",
    snacbarColor: "primary",
    model: {
      username: "John Smith",
      email: "email@exa.com",
      password: "admin",
      password_confirmation: ""
    },
    merrors: []
  }),

  methods: {
    register() {
      this.loading = true;
      this.$store
        .dispatch("REGISTRATION_REQUEST", this.model)
        .then(() => {
          this.snackbarText = "Your registration has been successfully completed";
          this.snacbarColor = "primary";
          this.snackbar = true;
          let that = this;
          setTimeout(function(){that.$router.push("/");}, 3000);

        })
        .catch(() => {
          this.snackbarText = "Error while registering";
          this.snacbarColor = "red";
          this.snackbar = true;
          this.model.password = "";
          this.model.password_confirmation = "";
          this.loading = false;
        });
    }
  }
};
</script>
<style scoped lang="css">
#login {
  height: 50%;
  width: 100%;
  position: absolute;
  top: 0;
  left: 0;
  content: "";
  z-index: 0;
}
</style>
