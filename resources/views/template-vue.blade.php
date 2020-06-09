<template>
  <vs-row vs-justify="center">
    <vs-col type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-xs="12">
      <vs-card>
        <vs-row vs-type="flex" vs-justify="center">
          <vs-col vs-type="flex" :vs-justify="this.justifyTitle" vs-align="center" vs-lg="6" vs-xs="12" >
            <h4 class="card-title d-flex">@{{ title }}</h4>
          </vs-col>
          <vs-col vs-type="flex" :vs-justify="this.justifyButton" vs-align="center" vs-lg="6" vs-xs="12" >
            <vs-button color="primary" type="filled" icon="add" size="small" class="mx-1" @click="newData()" >CREATE</vs-button>
            <vs-button color="warning" type="filled" icon="edit" size="small" class="mx-1" @click="editData()" >EDIT</vs-button>
            <vs-button color="danger" type="filled" icon="delete" size="small" class="mx-1" @click="deleteData()" >DELETE</vs-button>
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
              <vs-button button="submit" class="ml-auto" color="success" type="filled">Save</vs-button>
              <vs-button class="ml-2" color="danger" type="filled" @click="popup=false">Cancel</vs-button>
          </div>
        @endform
      @endpopup

    </vs-col>
  </vs-row>
</template>

<script>

export default {
  name: "{{$table}}",
  data: () => ({

    @foreach ($columns as $item)
       {{$item['column']}}: "",
    @endforeach

    endpoint: '{{$endpoint}}',
    token: localStorage.getItem('tokenType')+' '+localStorage.getItem('accessToken'),
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

    getData() {
      this.rows = [];
      this.axios({
         method: "get",
         url: this.endpoint +"?pageNum=" + (this.serverParams.page - 1) + "&pageSize=" + this.serverParams.perPage,
         timeout: 0,
         headers: {
            Accept: "application/json",
            Authorization: this.token
         }
      })
        .then(response => {
          this.serverParams.page = response.data.pageable.pageNumber + 1;
          this.serverParams.perPage = response.data.pageable.pageSize;
          this.totalRecords = response.data.totalElements;
          response.data.content.forEach(element => {
            this.rows.push(element);
          });
        })
        .catch(error => {
          this.$vs.notify({
            text: error,
            color: "danger",
            icon: "error"
          });
        });
    },
    // for table --- end ---

    newData() {
      @foreach ($columns as $item)
        this.{{$item['column']}} = "";
      @endforeach
      this.isNew = true;
      this.popupTitle = "Create " + this.title;
      this.popup = true;
    },

    editData() {
      let selected = this.$refs["VueGT"].selectedRows;
      if (selected.length != 1) {
        this.notify = {
          text:
            selected.length > 1
              ? "Does not support multiple edit, please select only one data"
              : "No data selected",
          color: "danger",
          icon: "error"
        };
        this.$vs.notify(this.notify);
        return;
      }

      this.axios({
         method: "get",
         url: this.endpoint +"/" + selected[0].nik,
         timeout: 0,
         headers: {
            Accept: "application/json",
            Authorization: this.token
         }
      })
        .then(response => {
          this.isNew = false;
          @foreach ($columns as $item)
            this.{{$item['column']}} = response.data.{{$item['column']}};
          @endforeach
          this.popupTitle = "Edit " + this.title;
          this.popup = true;
        })
        .catch(error => {
          this.$vs.notify({
            text: error,
            color: "danger",
            icon: "error"
          });
        });
    },

    save() {
      this.axios({
         method: this.isNew ? "post" : "put",
         url: this.endpoint,
         timeout: 0,
         headers: {
            Accept: "application/json",
            Authorization: this.token
         },
         data: {
            @foreach ($columns as $item)
              {{$item['column']}}: this.{{$item['column']}},
            @endforeach
         }
      })
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
        })
        .catch(error => {
          this.$vs.notify({
            text: error,
            color: "danger",
            icon: "error"
          });
        });
    },

    deleteData() {
      let selected = this.$refs["VueGT"].selectedRows;
      if (selected.length == 0) {
        this.notify = {
          text: "No data selected",
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
        text: "Are you sure want to delete this data ? ",
        accept: this.actDelete
      });
    },

    actDelete() {
      this.rows = [];
      this.$refs["VueGT"].selectedRows.forEach(row => {
        this.axios({
            method: "delete",
            url: this.endpoint +"/"+ row.nik,
            timeout: 0,
            headers: {
               Accept: "application/json",
               Authorization: this.token
            }
        })
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
          })
          .catch(error => {
            this.$vs.notify({
              text: error,
              color: "danger",
              icon: "error"
            });
          });
      });
      this.popup = false;
    },

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