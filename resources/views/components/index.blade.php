@switch($type)
   @case('text')
      @text([
         'title' => Str::upper($column),
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => Str::upper($title),
         'required' => $required
      ])
      @endtext
   @break
   
   @case('number')
      @textNumber([
         'title' => Str::upper($column),
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required
      ])
      @endtextNumber
      @break

   @default
      @text([
         'title' => Str::upper($column),
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required
      ])
      @endtext
      
@endswitch