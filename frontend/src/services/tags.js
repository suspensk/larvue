import $http from "./client";

export default {
  all: async tags => {
    let props = {};
    if(tags != undefined) {
      props.params = {
        q: tags
      };
    }
    const resp = await $http.get(`/tags`, props);
    return resp.data;
  },

  one: async tag => {
    const resp = await $http.get(`/tags/${tag}`);
    return resp.data;
  }
};
