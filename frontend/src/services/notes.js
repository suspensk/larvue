import $http from "./client";

export default {
  all: async () => {
    const resp = await $http.get(`/notes`);
    return resp.data;
  },

  one: async id => {
    const resp = await $http.get(`/notes/${id}`);
    return resp.data;
  },

  add: async data => {
    var fd = new FormData();
    // if(this.image!= "") {
    //   let picture = this.$refs.pictureInput.file;
    //   fd.append('image', picture);
    // }
    fd.append('text', data);
    fd.append('privacy', 0);
    fd.append('tags', {});

   // axios.post('api/notes', fd, {headers: { 'content-type': 'multipart/form-data' }})

    const resp = await $http.post('/notes', fd, {headers: { 'content-type': 'multipart/form-data' }});
    return resp.data;
  }
};
