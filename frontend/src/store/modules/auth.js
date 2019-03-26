import axios from "axios";
// TODO: use defined types

const state = {
  token: localStorage.getItem("jwt") || "",
  uid: localStorage.getItem("uid") || 0 ,
  name: localStorage.getItem("name") || "",
  email: localStorage.getItem("email") || "",
  status: ""
};

const getters = {
  isAuthenticated: state => !!state.token,
  authStatus: state => state.status,
  token: state => state.token,
  uid: state => state.uid,
  name: state => state.name,
  email: state => state.email
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
        url: "/api/login",
        data,
        method: "POST"
      })
        .then(resp => {
          const token = resp.data.success.token;
          const uid = resp.data.success.uid;
          const name = resp.data.success.name;
          const email = resp.data.success.email;
          localStorage.setItem("jwt", token); // store the token in localstorage
          localStorage.setItem("uid", uid);
          localStorage.setItem("name", name);
          localStorage.setItem("email", email);
          const payload = {"token": token, "uid": uid, "name": name, "email": email};
          commit("AUTH_SUCCESS", payload);
          // you have your token, now log in your user :)
          //dispatch("USER_REQUEST");
          resolve(resp);
        })
        .catch(err => {
          commit("AUTH_ERROR", err);
          localStorage.removeItem("jwt"); // if the request fails, remove any possible user token if possible
          reject(err);
        });
    });
  },
  ["AUTH_LOGOUT"]: ({ commit }) => {
    return new Promise(resolve => {
      commit("AUTH_LOGOUT");
      localStorage.removeItem("jwt"); // clear your user's token from localstorage
      resolve();
    });
  },
  ["REGISTRATION_REQUEST"]: ({ commit }, user) => {
    return new Promise((resolve, reject) => {
      // The Promise used for router redirect in login
      commit("AUTH_REQUEST");

      const data = {
        name: user.username,
        email: user.email,
        password: user.password,
        c_password : user.password_confirmation
      };
      // TODO: take from config
      axios({
        url: "/api/register",
        data,
        method: "POST"
      })
          .then(resp => {
            const token = resp.data.success.token;
            const uid = resp.data.success.uid;
            const name = resp.data.success.name;
            const email = resp.data.success.email;
            localStorage.setItem("jwt", token); // store the token in localstorage
            localStorage.setItem("uid", uid);
            localStorage.setItem("name", name);
            localStorage.setItem("email", email);
            const payload = {"token": token, "uid": uid, "name": name, "email": email};
            commit("AUTH_SUCCESS", payload);
            // you have your token, now log in your user :)
            //dispatch("USER_REQUEST");
            resolve(resp);
          })
          .catch(err => {
            commit("AUTH_ERROR", err);
            localStorage.removeItem("jwt"); // if the request fails, remove any possible user token if possible
            reject(err);
          });
    });
  },
};

// basic mutations, showing loading, success, error to reflect the api call status and the token when loaded

const mutations = {
  ["AUTH_REQUEST"]: state => {
    state.status = "loading";
  },
  ["AUTH_SUCCESS"]: (state, payload) => {
    state.status = "success";
    state.token = payload.token;
    state.uid = payload.uid;
    state.name = payload.name;
    state.email = payload.email;
    console.log(state.email)
  },
  ["AUTH_ERROR"]: state => {
    state.status = "error";
  },
  ["AUTH_LOGOUT"]: state => {
      axios({
          url: "/api/logout",
          method: "POST"
      })
          .then(resp => {
              state.token = "";
              state.uid = 0;
              state.name = "";
              state.email = "";
          })
          .catch(err => {
              reject(err);
          });

  }
};

export default {
  state,
  mutations,
  actions,
  getters
};
