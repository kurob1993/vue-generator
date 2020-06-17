<vs-row class="form-group">
    <vs-col vs-w="4" vs-xs="12">
       <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
    </vs-col>
    <vs-col vs-w="8" vs-xs="12">
       <ValidationProvider tag="div" rules="{{$required}}{{isset($max) ? '|max:'.$max : ''}}" name="{{$label}}" v-slot="{ errors }">
        <vs-select
            autocomplete
            class="selectExample"
            v-model="model.{{$vmodel}}"
        >
            <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in {{$vmodel}}Options" />
        </vs-select>
          <span class="text-danger d-block">@{{ errors[0] }}</span>
      </ValidationProvider>
    </vs-col>
</vs-row>