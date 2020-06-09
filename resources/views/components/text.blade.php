<vs-row class="form-group">
   <vs-col vs-w="4" vs-xs="12">
      <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
   </vs-col>
   <vs-col vs-w="8" vs-xs="12">
      <ValidationProvider tag="div" rules="{{$required}}" name="{{$label}}" v-slot="{ errors }">
         <vs-input 
            type="text" 
            class="inputx" 
            v-model="{{$vmodel}}" 
            id="{{$vmodel}}" 
            placeholder="{{isset($placeholder) ? $placeholder : ''}}" 
         />
         <span class="text-danger d-block">@{{ errors[0] }}</span>
     </ValidationProvider>
   </vs-col>
</vs-row>