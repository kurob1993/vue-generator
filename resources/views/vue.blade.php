<template>
  <vs-row vs-justify="center">
    <vs-col type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-xs="12">
      <vs-card>
        <h4 class="card-title d-flex">INPUT MASTER {{Str::upper($table)}}</h4>
        <vs-divider />
        @foreach ($columns as $item)
        @component('components.vs-input')
        @slot('type') {{$item['type']}} @endslot
        @slot('title') {{Str::upper($item['column'])}} @endslot
        @slot('label') {{Str::upper($item['column'])}} @endslot
        @slot('vmodel') {{$item['column']}} @endslot
        @slot('required') {{$item['required']}} @endslot
        @endcomponent
        @endforeach

        <vs-divider />
        <vs-button color="primary" type="filled" @click="save()">
          Save
        </vs-button>
      </vs-card>
    </vs-col>
  </vs-row>
</template>

<script>
  export default {
   name: "{{$table}}",
   data: () => ({
    errors: [],
    @foreach ($columns as $item)
      {{$item['column']}}: "",
    @endforeach

   }),
  methods: {
    save : function() {
      alert( 'test' )
      this.checkForm();
    },
    checkForm: function () {
      this.errors = [];
      @foreach ($columns as $item)
        @if($item['required'])

          if (!this.{{$item['column']}}) {
            this.errors.push("{{$item['column']}} required.");
          }

        @endif
      @endforeach

      if (!this.errors.length) {
        return true;
      }

      console.log(this.errors);
      

    }
  }
 };
</script>