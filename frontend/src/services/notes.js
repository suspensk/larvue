import $http from "./client";

export default {
  all: async () => {
    const resp = await $http.get(`/notes`);
    return resp.data;
  },

  one: async id => {
    const resp = await $http.get(`/notes/${id}`);
    return resp.data;
  }
};
