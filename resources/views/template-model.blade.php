@php($pk = [])
@foreach ($columns as $key => $item) 
@if($item['pk']) 
@php($pk[] = $item['column'])
@endif 
@endforeach

import {{ Str::title($table) }}Service from '@/services/{{ Str::limit($table,2,'') }}/{{ Str::lower($table) }}.service';

export default class {{ Str::title($table) }} {
  constructor(@foreach ($columns as $key  => $item) {{ ($key == 0 ? '' : ',') . $item['column'] }} @endforeach) {
    @foreach ($columns as $item)
        this.{{$item['column']}} = {{$item['column']}}
    @endforeach
  }

  async get(params) {
    return await {{ Str::title($table) }}Service.get(params);
  }

  async post() {
    return await {{ Str::title($table) }}Service.post({
      @foreach ($columns as $item)
          {{$item['column']}}: this.{{$item['column']}},
      @endforeach
    });
  }

  async put() {
    return await {{ Str::title($table) }}Service.put(
      @foreach ($columns as $key => $item) @if($item['pk']) 
      this.{{$item['column'] }},
      @endif @endforeach
      {
        @foreach ($columns as $item)
          {{$item['column']}}: this.{{$item['column']}},
        @endforeach
      }
    );
  }

  async delete({{ implode(",",$pk) }}) {
    return await {{ Str::title($table) }}Service.delete({{ implode(",",$pk) }});
  }

  async getById({{ implode(",",$pk) }}) {
    return await {{ Str::title($table) }}Service.getById({{ implode(",",$pk) }});
  }

  async getList() {
    let list = await {{ Str::title($table) }}Service.getList();
    return list.data.map(function(row) {
      let data = Object.values(row);
      return { value: data[0], text: data[1] };
    });
  }
}
