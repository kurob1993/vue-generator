<template>
  <vs-row vs-justify="center">
    <vs-col type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-xs="12">
      <vs-card id="with-loading" class="vs-con-loading__container">
        <vs-row vs-type="flex" vs-justify="center">
          <vs-col vs-type="flex" :vs-justify="this.justifyTitle" vs-align="center" vs-lg="6" vs-xs="12" >
            <h4 class="card-title d-flex">@{{ title }}</h4>
          </vs-col>
          <vs-col vs-type="flex" :vs-justify="this.justifyButton" vs-align="center" vs-lg="6" vs-xs="12" >
            <vs-button color="primary" type="filled" icon="add" size="small" class="mx-1" @click="newData()" >TAMBAH</vs-button>
            <vs-button id="edit-with-loading" color="warning" type="filled" icon="edit" size="small" class="vs-con-loading__container mx-1" @click="editData()" >UBAH</vs-button>
            <vs-button id="delete-with-loading" color="danger" type="filled" icon="delete" size="small" class="vs-con-loading__container mx-1" @click="deleteData()" >HAPUS</vs-button>
          </vs-col>
        </vs-row>

        <vs-divider />
        <GoodTable :model="dataModel" :columns="columns" ref="VueGT">
          <template slot-scope="{ VueGTprops }">
            @foreach ($columns as $item)
              @if ($item['type'] == 'switch')
                  <vs-chip v-if="VueGTprops.column.field == 'activestat'" :color="VueGTprops.row.activestat == 1 ? 'success' : ''">
                    <span v-if="VueGTprops.row.{{$item['column']}} == 1">Aktif</span>
                    <span v-else="">Tidak Aktif</span>
                  </vs-chip>
              @endif
            @endforeach
          </template>
        </GoodTable>
        <vs-divider />
      </vs-card>

      @popup([
        'title' => isset($title) ? Str::upper($title) : Str::upper($table),
        'popupActive' => 'popup'
      ])
        @form()
          @foreach ($columns as $item)
            @include('components.index', $item)
          @endforeach

          <vs-divider />
          <div class="d-flex">
            <vs-button button="submit" id="save-with-loading" class="vs-con-loading__container ml-auto" color="success" type="filled">Simpan</vs-button>
            <vs-button class="ml-2" color="danger" type="filled" @click="popup=false">Batal</vs-button>
          </div>
        @endform
      @endpopup

    </vs-col>
  </vs-row>
</template>

<script>
import {{Str::title($table)}} from '@/models/{{Str::limit($table,4,'')}}/{{$table}}'
@foreach ($columns as $item)
  @if($item['type'] == 'select')
  import {{Str::limit(Str::title($table),4,'')}}{{$item['column']}} from '@/models/{{Str::limit($table,4,'')}}/{{$item['column']}}'
  @endif
@endforeach
import GoodTable from '@/components/GoodTable';

export default {
  name: "{{$table}}",
  components: {
    GoodTable
  },
  data: () => ({
    model: new {{ Str::title($table) }}(),
    dataModel: {{ Str::title($table) }},
    title: "{{Str::upper($title)}}",

    notify: {},
    popupTitle: "",
    isNew: true,
    popup: false,
    @foreach ($columns as $item)
      @if($item['disabled'])
      {{$item['column']}}ReadOnly: false,
      @endif

      @if($item['type'] == 'select')
      {{$item['column']}}Options: {{Str::limit(Str::title($table),4,'')}}{{$item['column']}}.data,
      @endif
    @endforeach
    
    justifyTitle: "flex-start",
    justifyButton: "flex-end",
    window: { width: 0, height: 0 },   
    
    columns: [
      @foreach ($columns as $item)
        { label : '{{$item['title']}}', field: '{{ Str::lower($item['column'])}}'},
      @endforeach
    ]
  }),
  methods: {    
    /*
    * @newData() : inisialisasi data untuk membuat data baru
    *
    */
    newData() {
      @foreach ($columns as $item)
        @if($item['disabled'])
          this.{{$item['column']}}ReadOnly = false;
        @endif
        this.model.{{$item['column']}} = "{{ $item['type'] == 'switch' ? 0 : '' }}";
      @endforeach
      this.isNew = true;
      this.popupTitle = "TAMBAH " + this.title;
      this.popup = true;
    },

    /*
    * @editData() : inisialisasi data yang akan dirubah
    *
    */
    async editData() {
      let selected = this.$refs.VueGT.$refs.table.selectedRows;
      if (selected.length != 1) {
        this.notify = {
          text: selected.length > 1 ? "Silahkan pilih satu data" : "Tidak ada data terpilih",
          color: "danger",
          icon: "error"
        };
        this.$vs.notify(this.notify);
        return;
      }
      
      this.$vs.loading({ container: '#edit-with-loading', scale: 0.5 })

      let id = selected[0].{{$columns[0]['column']}};
      let {{ $table }} = new {{ Str::title($table) }}();
      let getById = await {{ $table }}.getById(id);
      if (getById.success) {
        @foreach ($columns as $item)
          @if($item['disabled'])
            this.{{$item['column']}}ReadOnly = true;
          @endif
          this.model.{{$item['column']}} = getById.data.{{$item['column']}};
        @endforeach
        this.isNew = false;
        this.popupTitle = 'UBAH ' + this.title;
        this.popup = true;
      }else{
        this.$vs.notify({ text: getById.data, color: 'danger', icon: 'error' });
      }
      this.$vs.loading.close('#edit-with-loading > .con-vs-loading');
    },

    /*
    * @save() : menyimpan data baru atau perubahan data
    *
    */
    async save() {
      this.$vs.loading({ container: '#save-with-loading', scale: 0.5 })
      let save = this.isNew ? await this.model.post() : await this.model.put();
      if (save.success) {
        this.$refs.VueGT.getData();
        this.popup = false;
        this.notify = { text: 'Sukses', color: 'success', icon: 'done' };
      }else{
        this.notify = { text: save.data, color: 'danger', icon: 'error' };
      }
      this.$vs.notify(this.notify);
      this.$vs.loading.close('#save-with-loading > .con-vs-loading');
    },

    /*
    * @deleteData() : inisialisasi data yang akan dihapsu
    *
    */
    deleteData() {
      let selected = this.$refs.VueGT.$refs.table.selectedRows;
      if (selected.length == 0) {
        this.notify = { text: "Tidak ada data terpilih", color: "danger", icon: "error" };
        this.$vs.notify(this.notify);
        return;
      }

      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: 'Konfirmasi',
        text: 'Apakah anda yakin menghapus data ini?',
        acceptText: 'Ya',
        cancelText: 'Tidak',
        accept: this.actDelete
      });
    },

    /*
    * @actDelete() : menghapus data sesuai data yang dipilih
    *
    */
    actDelete() {
      this.$refs.VueGT.$refs.table.selectedRows.forEach(async row =>  {
        this.$vs.loading({ container: '#delete-with-loading', scale: 0.5 });
        let id = row.{{$columns[0]['column']}};
        let hapus = await this.model.delete(id);
        if (hapus.success) {
          this.notify = { text: 'Sukses', color: 'success', icon: 'done' };
        }else{
          this.notify = { text: hapus.data, color: 'danger', icon: 'error' };
        }
        this.$refs.VueGT.getData();
        this.$vs.notify(this.notify);
        this.$vs.loading.close('#delete-with-loading > .con-vs-loading');
      });
      this.popup = false;
    },

    /*
    * @handleResize() : digunakan untuk mengatur propertis ketika ada perubhan screen
    *
    */
    handleResize() {
      if (window.innerWidth < 601) {
        this.justifyTitle = "center";
        this.justifyButton = "center";
      } else {
        this.justifyTitle = "flex-start";
        this.justifyButton = "flex-end";
      }
    }
  },
  computed: {
  @foreach ($columns as $item)
    @if ($item['type'] == 'switch')
      
        {{ $item['column'] }}Switch: {
          get(){
            return this.model.{{$item['column']}} == '1' ? true : false;
          },
          set(val) {
            this.model.{{$item['column']}} = val ? '1' : '0';
          }
        },
      
    @endif
  @endforeach
  },
  
  created() {
    window.addEventListener("resize", this.handleResize);
    this.handleResize();
  },

  destroyed() {
    window.removeEventListener("resize", this.handleResize);
  }
};
</script>

<style>
.con-vs-popup .vs-popup {
  width: 500px !important;
}

.vs-switch {
  width: 75px !important;
}
</style>