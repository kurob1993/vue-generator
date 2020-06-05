<template>
  <vs-row vs-justify="center">
    <vs-col type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-xs="12">
      <vs-card>
        <h4 class="card-title d-flex">{{ isset($title) ? Str::upper($title) : Str::upper($table)}}</h4>
        <vs-divider />

        @foreach ($columns as $item)
          @include('components.index', $item)
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
      
    }
  }
 };
</script>