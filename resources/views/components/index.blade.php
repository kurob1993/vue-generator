@switch($type)
   @case('text')
      @component('components.text',[
         'type' => $type,
         'title' => Str::upper($column),
         'label' => Str::upper($column),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required
      ])
      @endcomponent
   @break
   @case('number')
      @component('components.number',[
         'type' => $type,
         'title' => Str::upper($column),
         'label' => Str::upper($column),
         'vmodel' => $column,
         'required' => $required
      ])
      @endcomponent
      @break

   @default
   @component('components.text',[
         'type' => $type,
         'title' => Str::upper($column),
         'label' => Str::upper($column),
         'vmodel' => $column,
         'required' => $required
      ])
      @endcomponent
@endswitch