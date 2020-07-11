<vs-row class="form-group">
    <vs-col vs-w="4" vs-xs="5">
       <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
    </vs-col>
    <vs-col vs-w="8" vs-xs="7">
       <ValidationProvider tag="div" rules="{{$required}}{{isset($max) ? '|max:'.$max : ''}}" name="{{$label}}" v-slot="{ errors }">
            <select 
                autocomplete 
                class="b-select" 
                v-model="model.{{$vmodel}}"
                @if ($pk)
                :disabled='{{$vmodel}}ReadOnly'
                @endif
            >
                <option :key="index" :value="item.value" v-for="(item,index) in {{$vmodel}}Options" >
                    @{{item.text}}
                </option>
            </select>
            <span class="text-danger d-block">@{{ errors[0] }}</span>
        </ValidationProvider>
    </vs-col>
</vs-row>