<vs-row class="form-group">
    <vs-col vs-w="4" vs-xs="12">
        <label class="vs-input--label" for="{{$vmodel}}">{{$label}}</label>
    </vs-col>
    <vs-col vs-w="8" vs-xs="12">
        <vs-textarea
            v-model="{{$vmodel}}"
            id="{{$vmodel}}"
            {{$required}}
        />
    </vs-col>
</vs-row>