<vs-row class="form-group">
    <vs-col vs-w="4" vs-xs="12">
        <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
    </vs-col>
    <vs-col vs-w="8" vs-xs="12">
        <ValidationProvider tag="div" rules="{{$required}}" name="{{$label}}" v-slot="{ errors }">
            <vs-switch color="success" v-model="{{ $vmodel }}Switch">
                <span slot="on">Active</span>
                <span slot="off">Not Active</span>
            </vs-switch>
            <span class="text-danger d-block">@{{ errors[0] }}</span>
        </ValidationProvider>
    </vs-col>
</vs-row>