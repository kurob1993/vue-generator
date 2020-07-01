export default {
    data: [
    @foreach ($relasi as $key => $item)
        {value: '{{$item['value']}}', text: '{{$item['text']}}' }{{ count($relasi)-1 !== $key ? ',' : ''}}
    @endforeach
    ]
}