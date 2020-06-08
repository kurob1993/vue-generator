<vs-input 
   type="text" 
   label="{{$label}}" 
   class="my-4" 
   style="width:100%" 
   placeholder="{{isset($placeholder) ? $placeholder : ''}}"
   v-model="{{$vmodel}}" 
   {{$required}} 
/>