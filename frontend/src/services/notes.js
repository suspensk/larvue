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

  more: async id => {
    const resp = await $http.get(`/notes/${id}`);
    return resp.data.text;
  },

  add: async fd => {
     // axios.post('api/notes', fd, {headers: { 'content-type': 'multipart/form-data' }})

    const resp = await $http.post('/notes', fd, {headers: { 'content-type': 'multipart/form-data' }});
    return resp.data;
  },

  delete: async id => {
      await $http.delete('/notes/' + id);

      /*axios.delete('api/notes/' + note_id, {}).then(response => {
          that.$notify({
              group: 'foo',
              title: 'Success',
              type: 'success',
              text: 'deleted successfully'
          });
          console.log(response.data);
          this.init();
      }).catch(error => {
          that.$notify({
              group: 'foo',
              title: 'Error',
              type: 'error',
              text: error.response.data.message
          });
          //  console.error(error.response.data.error);
      });*/
  }
};
