<template>
  <v-container fluid fill-height>
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
                name="login"
                label="Login"
                type="text"
                v-model="model.username"
              ></v-text-field>
              <v-text-field
                append-icon="lock"
                name="password"
                label="Password"
                id="password"
                type="password"
                v-model="model.password"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn block color="primary" @click="login" :loading="loading"
              >Login</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  data: () => ({
    loading: false,
    model: {
      username: "email@exa.com",
      password: "admin"
    },
    merrors: []
  }),

  methods: {
    login() {
      this.loading = true;
      this.$store
        .dispatch("AUTH_REQUEST", this.model)
        .then(() => {
          this.$router.push("/");
        })
        .catch(() => {
          this.model.username = "";
          this.model.password = "";
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
