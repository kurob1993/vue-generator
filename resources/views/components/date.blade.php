<vs-row class="form-group">
    <vs-col vs-w="4" vs-xs="5">
        <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
    </vs-col>
    <vs-col vs-w="8" vs-xs="7">
        <ValidationProvider tag="div" rules="{{$required}}" name="{{$label}}" v-slot="{ errors }">
            <vs-input 
                type="date" 
                class="inputx" 
                v-model="model.{{$vmodel}}" 
                id="{{$vmodel}}" 
                @if ($pk)
                :disabled='{{$vmodel}}ReadOnly'
                @endif
            />
            <span class="text-danger d-block">@{{ errors[0] }}</span>
        </ValidationProvider>
    </vs-col>
</vs-row>