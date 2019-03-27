import axios from "axios";
// TODO: use defined types

const state = {
  tags: [],
};

const getters = {
  tags: state => state.tags
};

const actions = {
  ["TAGS_REQUEST"]: ({ commit }) => {
    return new Promise((resolve, reject) => {
      // The Promise used for router redirect in login
      commit("TAGS_REQUEST");
      
      axios({
        url: "/api/tags",
        method: "GET"
      })
        .then(resp => {
          const tags = resp.data;
          commit("TAGS_SUCCESS", tags);
          resolve(resp);
        })
        .catch(err => {
          commit("TAGS_ERROR", err);
          reject(err);
        });
    });
  },
};

// basic mutations, showing loading, success, error to reflect the api call status and the token when loaded

const mutations = {
  ["TAGS_REQUEST"]: state => {
    state.status = "loading";
  },
  ["TAGS_SUCCESS"]: (state, tags) => {
    state.status = "success";
    state.tags = tags;
  },
  ["TAGS_ERROR"]: state => {
    state.status = "error";
  },
  ["AUTH_LOGOUT"]: state => {
 //   state.tags = [];
  }
};

export default {
  state,
  mutations,
  actions,
  getters
};
