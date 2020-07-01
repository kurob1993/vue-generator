@php($pk = [])
@foreach ($columns as $key => $item) 
@if($item['pk']) 
@php($pk[] = $item['column'])
@endif 
@endforeach

import axios from 'axios';
import authHeader from '../auth-header';

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

  put({{ implode(",",$pk) }}, data) {
    return this.service('put', API_URL @foreach ($columns as $key => $item) @if($item['pk']) + '/' + {{ $item['column'] }} @endif @endforeach, data);
  }

  delete({{ implode(",",$pk) }}) {
    return this.service('delete', API_URL @foreach ($columns as $key => $item) @if($item['pk']) + '/' + {{ $item['column'] }} @endif @endforeach);
  }

  getById({{ implode(",",$pk) }}) {
    return this.service('get', API_URL @foreach ($columns as $key => $item) @if($item['pk']) + '/' + {{ $item['column'] }} @endif @endforeach);
  }

  getList() {
    return this.service('get', API_URL + '/getlist');
  }
}

export default new {{ Str::title($table) }}();
