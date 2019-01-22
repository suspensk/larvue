import $http from "./client";

export default {
  all: async () => {
    const resp = await $http.get(`/tags`);
    return resp.data;
  },

  one: async tag => {
    const resp = await $http.get(`/rags/${tag}`);
    return resp.data;
  }
};
