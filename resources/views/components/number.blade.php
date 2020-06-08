<vs-row class="form-group">
   <vs-col vs-w="4" vs-xs="12">
      <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
   </vs-col>
   <vs-col vs-w="8" vs-xs="12">
      <vs-input-number v-model="{{$vmodel}}" id="{{$vmodel}}" min="0" max="10" :step="1" />
   </vs-col>
</vs-row>