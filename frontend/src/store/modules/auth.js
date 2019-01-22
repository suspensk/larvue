import axios from "axios";
// TODO: use defined types

const state = {
  token: localStorage.getItem("user-token") || "",
  status: ""
};

const getters = {
  isAuthenticated: state => !!state.token,
  authStatus: state => state.status,
  token: state => state.token
};

const actions = {
  ["AUTH_REQUEST"]: ({ commit }, user) => {
    return new Promise((resolve, reject) => {
      // The Promise used for router redirect in login
      commit("AUTH_REQUEST");

      const data = {
        email: user.username,
        password: user.password
      };
      // TODO: take from config
      axios({
        url: "/login",
        data,
        method: "POST"
      })
        .then(resp => {
          const token = resp.data.token;
          localStorage.setItem("user-token", token); // store the token in localstorage
          commit("AUTH_SUCCESS", token);
          // you have your token, now log in your user :)
          //dispatch("USER_REQUEST");
          resolve(resp);
        })
        .catch(err => {
          commit("AUTH_ERROR", err);
          localStorage.removeItem("user-token"); // if the request fails, remove any possible user token if possible
          reject(err);
        });
    });
  },
  ["AUTH_LOGOUT"]: ({ commit }) => {
    return new Promise(resolve => {
      commit("AUTH_LOGOUT");
      localStorage.removeItem("user-token"); // clear your user's token from localstorage
      resolve();
    });
  }
};

// basic mutations, showing loading, success, error to reflect the api call status and the token when loaded

const mutations = {
  ["AUTH_REQUEST"]: state => {
    state.status = "loading";
  },
  ["AUTH_SUCCESS"]: (state, token) => {
    state.status = "success";
    state.token = token;
  },
  ["AUTH_ERROR"]: state => {
    state.status = "error";
  },
  ["AUTH_LOGOUT"]: state => {
    state.token = "";
  }
};

export default {
  state,
  mutations,
  actions,
  getters
};
