@switch($type)
   @case('text')
      @text([
         'type' => $type,
         'title' => Str::upper($column),
         'label' => Str::upper($column),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required
      ])
      @endtext
   @break
   @case('number')
      @textNumber([
         'type' => $type,
         'title' => Str::upper($column),
         'label' => Str::upper($column),
         'vmodel' => $column,
         'required' => $required
      ])
      @endtextNumber
      @break

   @default
      @text([
         'type' => $type,
         'title' => Str::upper($column),
         'label' => Str::upper($column),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required
      ])
      @endtext
      
@endswitch