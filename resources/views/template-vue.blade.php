<template>
   <vs-row vs-justify="center">
      <vs-col type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-xs="12">
         <vs-card>
            <vs-row vs-type="flex" vs-justify="center">
               <vs-col vs-type="flex" :vs-justify="this.justifyTitle" vs-align="center" vs-lg="6" vs-xs="12">
                  <h4 class="card-title d-flex">{{ isset($title) ? Str::upper($title) : Str::upper($table)}}</h4>
               </vs-col>
               <vs-col vs-type="flex" :vs-justify="this.justifyButton" vs-align="center" vs-lg="6" vs-xs="12">
                  <vs-button color="primary" type="filled" icon="add" size="small" class="mx-1"
                     @click="popupActive=true">CREATE</vs-button>
                  <vs-button color="warning" type="filled" icon="edit" size="small" class="mx-1">EDIT</vs-button>
                  <vs-button color="danger" type="filled" icon="delete" size="small" class="mx-1">DELETE</vs-button>
               </vs-col>
            </vs-row>

            <vs-divider />
            <vue-good-table 
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
            />
            <vs-divider />
         </vs-card>

         <vs-popup classContent="popup-example" title="CREATE {{ isset($title) ? Str::upper($title) : Str::upper($table)}}" :active.sync="popupActive">
            @foreach ($columns as $item)
              @include('components.index', $item)
            @endforeach
            <vs-divider />
            <div class="d-flex">
               <vs-button class="ml-auto" color="success" type="filled">Save</vs-button>
               <vs-button class="ml-2" color="danger" type="filled">Cancel</vs-button>
            </div>
         </vs-popup>
         
      </vs-col>
   </vs-row>
</template>

<script>
   // const axios = require('axios');

export default {
  name: "dokter",
  data: () => ({
   
   @foreach ($columns as $item)
      {{$item['column']}}: "",
   @endforeach

   justifyTitle: "flex-start",
   justifyButton: "flex-end",
   window: { width: 0, height: 0 },
   popupActive: false,
   errors: [],
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
      this.updateParams({page: params.currentPage});
      this.loadItems();
    },

    onPerPageChange(params) {
      this.updateParams({perPage: params.currentPerPage});
      this.loadItems();
    },

    onSortChange(params) {
      this.updateParams({
        sort: [{
          type: params.sortType,
          field: this.columns[params.columnIndex].field,
        }],
      });
      this.loadItems();
    },
    
    onColumnFilter(params) {
      this.updateParams(params);
      this.loadItems();
    },

    // load items is what brings back the rows from server
    loadItems() {
      console.log(this.serverParams);
      this.getData();     
    },
    getData() {
      this.rows = [];
      this.axios({
        method: 'get',
        url: '{{$endpoint}}?pageNum='+(this.serverParams.page-1 )+'&pageSize='+this.serverParams.perPage,
        timeout: 0,
        headers: {
          "Accept": "application/json",
          "Authorization": "Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJrdXJvYiIsImlhdCI6MTU5MTYxNTcyOCwiZXhwIjoxNTkxNzAyMTI4fQ.Gqnzei_nMoA6mpsnoBASxRknDxcShNey_ZBCbcHWBP-xfKm9J3XyDwbQtswCrxRYBuMK3Btmg282fMdaxgNBRA"
        },
      })
      .then(response => {
        this.serverParams.page = response.data.pageable.pageNumber+1;
        this.serverParams.perPage = response.data.pageable.pageSize;
        this.totalRecords = response.data.totalElements;
        response.data.content.forEach(element => {
          this.rows.push(element);
        });
      });
    },
    // for table --- end ---

    save() {
      alert("test");
      this.checkForm();
    },

    checkForm: function() {
      this.errors = [];

      if (!this.nik) {
        this.errors.push("nik required.");
      }

      if (!this.errors.length) {
        return true;
      }
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