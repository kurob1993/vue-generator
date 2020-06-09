<vs-row class="form-group">
    <vs-col vs-w="4" vs-xs="12">
        <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
    </vs-col>
    <vs-col vs-w="8" vs-xs="12">
        <vs-switch color="success" v-model="{{$vmodel}}" id="{{$vmodel}}">
            <span slot="0">Active</span>
            <span slot="1">Not Active</span>
        </vs-switch>
    </vs-col>
</vs-row>