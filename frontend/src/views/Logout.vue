<template>
</template>

<script>
export default {
  created() {
    this.$store
    .dispatch("AUTH_LOGOUT").
    then(()=>{
    this.$radio.$emit('show-notice', 'primary', 'You have been successfully logged out');
    this.$router.push("/login");
  })
.catch((e) => {
  this.show-notice("red",e)
});
  }
};
</script>