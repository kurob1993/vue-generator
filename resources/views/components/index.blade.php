@switch($type)
   @case('text')
      @text([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => Str::upper($title),
         'required' => $required,
         'max' => $max
      ])
      @endtext
      @break

   @case('textarea')
      @textarea([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max
      ])
      @endtextarea
      @break

   @case('switch')
      @switchInput([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max
      ])
      @endswitchInput
      @break
   
   @case('number')
      @number([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max
      ])
      @endnumber
      @break

   @case('textNumber')
      @textNumber([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max
      ])
      @endtextNumber
      @break

   @case('date')
      @date([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max
      ])
      @enddate
      @break
      
   @default
      @text([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required,
         'max' => $max
      ])
      @endtext
      
@endswitch