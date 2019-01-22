import axios from "axios";

const $http = axios.create({
  baseURL: process.env.VUE_APP_API_BASE,
  validateStatus: () => {
    return true; // I'm always returning true, you may want to do it depending on the status received
  }
});

$http.interceptors.request.use(
  config => {
    config.headers.authorization = "Bearer " + (() => "fake JWT")();
    return config;
  },
  error => Promise.reject(error)
);

$http.interceptors.response.use(
  function(response) {
    if (response.status === 401) {
      console.log("Unauthorized"); // logout
    }
    return response;
  },
  function(error) {
    const {
      response: { status }
    } = error;
    if (status === 401) {
      console.log("Unauthorized"); // logout
    }
    return Promise.reject(error);
  }
);

export default $http;
