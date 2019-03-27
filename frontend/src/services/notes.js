import $http from "./client";

export default {
  all: async (tags, feed) => {
    let props = {};
    props.params = {};
    props.params.feed = feed;
    if(tags != undefined && tags.length) {
      props.params.tags = tags;
    }
    const resp = await $http.get(`/notes`, props);
    return resp.data;
  },

  one: async id => {
    const resp = await $http.get(`/notes/${id}`);
    return resp.data;
  },

  count: async () => {
    const resp = await $http.get(`/count`);
    return resp.data;
  },

  more: async id => {
    const resp = await $http.get(`/notes/${id}`);
    return resp.data.text;
  },

  add: async fd => {
     // axios.post('api/notes', fd, {headers: { 'content-type': 'multipart/form-data' }})

    const resp = await $http.post('/notes', fd, {headers: { 'content-type': 'multipart/form-data' }});
    return resp.data;
  },
  update: async (fd,id) => {
    const resp = await $http.post(`/notes/${id}`, fd, {headers: { 'content-type': 'multipart/form-data' }});
    return resp.data;
  },

  delete: async id => {
      await $http.delete(`/notes/${id}`);
  }
};
