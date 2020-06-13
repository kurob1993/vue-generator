import {{ Str::title($table) }}Service from '@/services/{{ Str::lower($table) }}.service';

export default class {{ Str::title($table) }} {
  constructor(@foreach ($columns as $key  => $item) {{ ($key == 0 ? '' : ',') . $item['column'] }} @endforeach) {
    @foreach ($columns as $item)
        this.{{$item['column']}} = {{$item['column']}}
    @endforeach
  }

  async get(params) {
    let data = await {{ Str::title($table) }}Service.get(params);
    let json = data.data;
    let res = { success: true, data: {} };

    if (data.success) {
      res.data = {
        rows: [],
        totalRecords: 0,
        serverParams: {
          page: 1,
          perPage: 10
        }
      };

      res.data.rows = json.content;
      res.data.totalRecords = json.totalElements;
      res.data.serverParams.page = json.pageable.pageNumber + 1;
      res.data.serverParams.perPage = json.pageable.pageSize;
    } else {
      res.success = false;
      res.data = json;
    }

    return res;
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
