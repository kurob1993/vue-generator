import axios from 'axios';
import authHeader from './auth-header';

const API_URL = '{{$endpoint}}';

class {{ Str::title($table) }}Service {
  get(params) {
    return axios.get(API_URL + params, { headers: authHeader() });
  }

  post(data) {
    return axios.post(API_URL, data, { headers: authHeader() });
  }

  put(data) {
    return axios.put(API_URL, data, { headers: authHeader() });
  }

  delete(id) {
    return axios.delete(API_URL +'/'+ id, { headers: authHeader() });
  }

  getById(id) {
    return axios.get(API_URL +'/'+ id, { headers: authHeader() });
  }
}

export default new {{ Str::title($table) }}Service();
