<template>
  <vue-word-cloud
    :words="words"
    :color="color"
    font-family="Roboto"
    :spacing="1 / 5"
    :font-size-ratio="1 / 4"
  >
    <template slot-scope="{text, weight, word}">
      <div style="cursor: pointer;" @click="onWordClick(word)">
        {{ text }}
      </div>
    </template>

  </vue-word-cloud>
</template>

<script>
import VueWordCloud from "vuewordcloud";
import TagsService from "@/services/tags";

export default {
  name: "TagCloud",
  components: {
    VueWordCloud
  },
  data() {
    return {
      words: [],
      color: () =>
        ["#ffd077", "#3bc4c7", "#3a9eea", "#ff4e69", "#461e47"][
          Math.floor(Math.random() * 5)
        ]
    };
  },

  async created() {
    const res = await TagsService.all();
    res.forEach(tag => {
      this.words.push([tag.name, tag.user_id]);
    });
  },

  methods: {
    onWordClick: function(word) {
      this.$router.push("/notes?tags="+ word[0]);
    },
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="stylus"></style>
