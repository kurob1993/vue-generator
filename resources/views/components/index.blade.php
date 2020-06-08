@switch($type)
   @case('text')
      @text([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => Str::upper($title),
         'required' => $required
      ])
      @endtext
   @break
   
   @case('number')
      @number([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required
      ])
      @endtextNumber
      @break

   @case('textNumber')
      @textNumber([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required
      ])
      @endtextNumber
      @break

   @default
      @text([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required
      ])
      @endtext
      
@endswitch