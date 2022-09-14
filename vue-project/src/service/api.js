import axios from "axios";

const baseUrl = import.meta.env.VITE_API_URL + "/" + import.meta.env.VITE_API_PREFIX + "/";

// Axios - add JWT token to auth headers
axios.interceptors.request.use(config => {
      config.headers.Authorization = `Bearer ${import.meta.env.VITE_JWT_BEARER_TOKEN}`;
      return config;
});

export const makeRequest = async (method = "get", entities = "applications", id = null, data = {}) => {
    console.log(baseUrl);
  if (method.toLowerCase() === "get") {
    if (id == null) {
      const url = `${baseUrl}${entities}`;
      return sendRequest("get", url);
    }
  }

  /* if (method.toUpperCase() === "POST") {
    switch (controlName) {
      case "test":
        return sendRequest(`${endpoints.lightMain}/${controlAction}`, data);
      default:
        console.log(`Endpoint for "${controlName}" does not exists.`);
        return;
    }
  } else {
    console.log(`Unsupported request method: "${method}"`);
    return;
  }*/
};

// async: axios is returning a Promise
const sendRequest = async (method = "get", url = "", data = {}) => {
  try {
    switch (method) {
      case "get":
        return axios.get(url, data || {});
        break;
      case "post":
        return axios.post(url, data || {});
        break;
      case "put":
        return axios.put(url, data || {});
        break;
      case "delete":
        return axios.delete(url);
        break;
      default:
        throw "Unsupported method: " + method;
        break;
    }
  } catch (error) {
    console.error(error);
  }

};
