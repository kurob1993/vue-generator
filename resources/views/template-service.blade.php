import axios from 'axios';
import authHeader from './auth-header';

const API_URL = '{{ $endpoint }}';

class {{ Str::title($table) }} {
  service(method, url, data){
    return axios({
      url: url,
      method: method,
      headers: authHeader(),
      data: data
    })
    .then(response => {
      return { 'success': true, 'data': response.data };
    })
    .catch(error => {
      return { 'success': false, 'data': error.response.data.message };
    });
  }

  get(params) {
    return this.service('get', API_URL + params);
  }

  post(data) {
    return this.service('post', API_URL, data);
  }

  put(data) {
    return this.service('put', API_URL, data);
  }

  delete(id) {
    return this.service('delete', API_URL + '/' + id);
  }

  getById(id) {
    return this.service('get', API_URL + '/' + id);
  }
}

export default new {{ Str::title($table) }}();
