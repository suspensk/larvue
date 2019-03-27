import * as types from "../mutation-types";
//import axios from "axios";
import $http from "../../services/client";

const state = {
  sidebar: {
    opened: false,
    withoutAnimation: false
  },
  config: {
    googleMaps: {
      apiKey: "AIzaSyBNAqPrTQoz9P4NBlDDyfxrnKiafkaL8iQ"
    },
    windowMatchSizeLg: "(min-width: 992px)",
    palette: {
      primary: "#4ae387",
      danger: "#e34a4a",
      info: "#4ab2e3",
      success: "#db76df",
      warning: "#f7cc36",
      white: "#fff",
      black: "#000",
      fontColor: "#34495e",
      transparent: "transparent",
      lighterGray: "#ddd"
    }
  },
  isLoading: true,
  feed: localStorage.getItem("feed") || false,
  nCount: localStorage.getItem("nCount") || 0,
  fCount: localStorage.getItem("fCount") || 0,

};

const getters = {
  feed: state => state.feed,
  nCount: state => state.nCount,
  fCount: state => state.fCount,
};

const actions = {
  closeMenu({ commit }) {
    commit(types.CLOSE_MENU);
  },
  toggleSidebar({ commit }, opened) {
    commit(types.TOGGLE_SIDEBAR, opened);
  },
  isToggleWithoutAnimation({ commit }, value) {
    commit(types.TOGGLE_WITHOUT_ANIMATION, value);
  },
  ["FEED_REQUEST"]: ({ commit }, value) => {
    localStorage.setItem("feed", value);
    commit("FEED_REQUEST", value);
  },
  ["COUNT_REQUEST"]: ({ commit }) => {
    return new Promise((resolve, reject) => {
      $http({
        url: "/count",
        method: "GET"
      })
          .then(resp => {
            localStorage.setItem("nCount", resp.data.notes);
            localStorage.setItem("fCount", resp.data.feed);
            commit("COUNT_REQUEST",resp.data);
            resolve(resp);
          })
          .catch(err => {
            reject(err);
          });
    });
  },

};

const mutations = {
  [types.CLOSE_MENU](state) {
    if (document.documentElement.clientWidth < 992) {
      state.sidebar.opened = false;
    }
  },
  [types.TOGGLE_SIDEBAR](state, opened) {
    state.sidebar.opened = opened;
  },
  [types.TOGGLE_WITHOUT_ANIMATION](state, value) {
    state.sidebar.withoutAnimation = value;
  },
  setLoading(state, isLoading) {
    state.isLoading = isLoading;
  },
  ["FEED_REQUEST"]: (state, value) => {
    state.feed = value;
  },
  ["COUNT_REQUEST"]: (state, payload) => {
    state.nCount = payload.notes;
    state.fCount = payload.feed;
  },
};



export default {
  state,
  mutations,
  actions,
  getters
};
