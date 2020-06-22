@switch($type)
   @case('text')
      @text([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => Str::upper($title),
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endtext
      @break

   @case('select')
      @select([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => Str::upper($title),
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endselect
      @break

   @case('textarea')
      @textarea([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endtextarea
      @break

   @case('switch')
      @switchInput([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endswitchInput
      @break
   
   @case('number')
      @number([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endnumber
      @break

   @case('textNumber')
      @textNumber([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endtextNumber
      @break

   @case('date')
      @date([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @enddate
      @break
      
   @default
      @text([
         'label' => Str::upper($title),
         'vmodel' => $column,
         'placeholder' => '',
         'required' => $required,
         'max' => $max,
         'pk' => $pk ? true : false
      ])
      @endtext
      
@endswitch