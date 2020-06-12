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
        <vue-good-table
          ref="VueGT"
          mode="remote"
          @on-page-change="onPageChange"
          @on-sort-change="onSortChange"
          @on-column-filter="onColumnFilter"
          @on-per-page-change="onPerPageChange"
          :totalRows="totalRecords"
          :isLoading.sync="isLoading"
          :pagination-options="{
            enabled: true,
          }"
          :rows="rows"
          :columns="columns"
          :sort-options="{
            enabled: false,
          }"
          :select-options="{ 
            enabled: true,
          }"
        />
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
            <vs-button button="submit" id="save-with-loading" class="vs-con-loading__container ml-auto" color="success" type="filled">Save</vs-button>
            <vs-button class="ml-2" color="danger" type="filled" @click="popup=false">Cancel</vs-button>
          </div>
        @endform
      @endpopup

    </vs-col>
  </vs-row>
</template>

<script>
import {{ Str::title($table) }} from '@/models/{{$table}}'
import {{ Str::title($table) }}Service from '@/services/{{$table}}.service'

export default {
  name: "{{$table}}",
  data: () => ({
    model: new {{ Str::title($table) }}(),
    title: "{{Str::upper($title)}}",
    isNew: true,
    justifyTitle: "flex-start",
    justifyButton: "flex-end",
    window: { width: 0, height: 0 },
    popupTitle: "",
    popup: false,
    notify: {},
    //for table start
    isLoading: false,
    columns: [
      @foreach ($columns as $item)
        { label : '{{$item['title']}}', field: '{{ Str::lower($item['column'])}}'},
      @endforeach
    ],
    rows: [],
    totalRecords: 0,
    serverParams: {
      page: 1,
      perPage: 10
    }
    //for table end
  }),
  methods: {
    // for table --- start ---
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },

    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.loadItems();
    },

    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.loadItems();
    },

    onSortChange(params) {
      this.updateParams({
        sort: [
          {
            type: params.sortType,
            field: this.columns[params.columnIndex].field
          }
        ]
      });
      this.loadItems();
    },

    onColumnFilter(params) {
      this.updateParams(params);
      this.loadItems();
    },

    // load items is what brings back the rows from server
    loadItems() {
      this.getData();
    },

    /*
    * @getData() : inisialisasi data untuk ditampikan dalam tabel
    *
    */
    getData() {
      this.$vs.loading({
        container: '#with-loading'
      })
      this.rows = [];
        let query = "?pageNum=" + this.serverParams.page + "&pageSize=" + this.serverParams.perPage;
        {{ Str::title($table) }}Service.get(query)
        .then(response => {
          this.serverParams.page = response.data.pageable.pageNumber+1;
          this.serverParams.perPage = response.data.pageable.pageSize;
          this.totalRecords = response.data.totalElements;
          response.data.content.forEach(element => {
            this.rows.push(element);
          });
          this.$vs.loading.close('#with-loading > .con-vs-loading')
        })
        .catch(error => {
          this.$vs.notify({
            text: error,
            color: "danger",
            icon: "error"
          });
          this.$vs.loading.close('#with-loading > .con-vs-loading')
        });
    },
    // for table --- end ---
    
    /*
    * @newData() : inisialisasi data untuk membuat data baru
    *
    */
    newData() {
      @foreach ($columns as $item)
        this.model.{{$item['column']}} = "";
      @endforeach
      this.isNew = true;
      this.popupTitle = "Create " + this.title;
      this.popup = true;
    },

    /*
    * @editData() : inisialisasi data yang akan dirubah
    *
    */
    editData() {
      let selected = this.$refs["VueGT"].selectedRows;
      if (selected.length != 1) {
        this.notify = {
          text:
            selected.length > 1
              ? "Silahkan pilih satu data"
              : "Tidak ada data terpilih",
          color: "danger",
          icon: "error"
        };
        this.$vs.notify(this.notify);
        return;
      }

      this.$vs.loading({
        container: '#edit-with-loading',
        scale: 0.5
      })

      let id = selected[0].{{$columns[0]['column']}};
      {{ Str::title($table) }}Service.getById(id)
        .then(response => {
          this.isNew = false;
          @foreach ($columns as $item)
            this.model.{{$item['column']}} = response.data.{{$item['column']}};
          @endforeach
          this.popupTitle = "Edit " + this.title;
          this.popup = true;
          this.$vs.loading.close('#edit-with-loading > .con-vs-loading')
        })
        .catch(error => {
          this.$vs.notify({
            text: error,
            color: "danger",
            icon: "error"
          });
          this.$vs.loading.close('#edit-with-loading > .con-vs-loading')
        });
    },

    /*
    * @save() : menyimpan data baru atau perubahan data
    *
    */
    save() {
      this.$vs.loading({
        container: '#save-with-loading',
        scale: 0.5
      })
      let data = this.model;
      {{ Str::title($table) }}Service[(this.isNew ? "post" : "put")](data)
        .then(response => {
          if (response.status == 200) {
            this.getData();
            this.popup = false;
            this.notify = {
              text: "Success",
              color: "success",
              icon: "done"
            };
          } else {
            this.notify = {
              text: response.statusText,
              color: "danger",
              icon: "error"
            };
          }

          this.$vs.notify(this.notify);
          this.$vs.loading.close('#save-with-loading > .con-vs-loading')
        })
        .catch(error => {
          this.$vs.notify({
            text: error,
            color: "danger",
            icon: "error"
          });
          this.$vs.loading.close('#save-with-loading > .con-vs-loading')
        });
    },

    /*
    * @deleteData() : inisialisasi data yang akan dihapsu
    *
    */
    deleteData() {
      let selected = this.$refs["VueGT"].selectedRows;
      if (selected.length == 0) {
        this.notify = {
          text: "Tidak ada data terpilih",
          color: "danger",
          icon: "error"
        };
        this.$vs.notify(this.notify);
        return;
      }

      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: "Confirm",
        text: "Apakah anda yakin menghapus data ini?",
        accept: this.actDelete
      });
    },

    /*
    * @actDelete() : menghapus data sesuai data yang dipilih
    *
    */
    actDelete() {
      this.rows = [];
      this.$refs["VueGT"].selectedRows.forEach(row => {
        this.$vs.loading({
          container: '#delete-with-loading',
          scale: 0.5
        })
        
        let id = row.{{$columns[0]['column']}};
        {{ Str::title($table) }}Service.delete(id)
          .then(response => {
            if (response.status == 200) {
              this.notify = {
                text: "Success",
                color: "success",
                icon: "done"
              };
            } else {
              this.notify = {
                text: response.statusText,
                color: "danger",
                icon: "error"
              };
            }
            this.getData();
            this.$vs.notify(this.notify);
            this.$vs.loading.close('#delete-with-loading > .con-vs-loading')
          })
          .catch(error => {
            this.$vs.notify({
              text: error,
              color: "danger",
              icon: "error"
            });
            this.$vs.loading.close('#delete-with-loading > .con-vs-loading')
          });
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
  mounted() {
    this.getData();
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