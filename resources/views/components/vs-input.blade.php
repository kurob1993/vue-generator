<div class="default-input">
   <vs-input 
      type="{{$type}}" 
      label="{{$label}}" 
      class="my-4" 
      style="width:100%" 
      placeholder="{{isset($Placeholder) ? $Placeholder : ''}}"
      v-model="{{$vmodel}}" 
      {{$required}} />
</div>