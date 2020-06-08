<vs-popup classContent="popup-example" title="{{$title}}" :active.sync="{{$popupActive}}">
   {{ $slot }}
   <vs-divider />
   <div class="d-flex">
      <vs-button class="ml-auto" color="success" type="filled">Save</vs-button>
      <vs-button class="ml-2" color="danger" type="filled" @click="{{$popupActive}}=false">Cancel</vs-button>
   </div>
</vs-popup>