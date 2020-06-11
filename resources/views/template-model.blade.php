export default class {{ Str::title($table) }} {

  constructor(@foreach ($columns as $key  => $item) {{ ($key == 0 ? '' : ',') . $item['column'] }} @endforeach) {
    @foreach ($columns as $item)
        this.{{$item['column']}} = {{$item['column']}}
    @endforeach
  }
}