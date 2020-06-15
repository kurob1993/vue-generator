import {{ Str::title($table) }}Service from '@/services/{{ Str::lower($table) }}.service';

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
    return await {{ Str::title($table) }}Service.put({
      @foreach ($columns as $item)
          {{$item['column']}}: this.{{$item['column']}},
      @endforeach
    });
  }

  async delete(id) {
    return await {{ Str::title($table) }}Service.delete(id);
  }

  async getById(id) {
    return await {{ Str::title($table) }}Service.getById(id);
  }
}
