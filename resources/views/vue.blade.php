<template>
   <div>
      <!-- begin breadcrumb -->
      <ol class="breadcrumb float-xl-right">
         <li class="breadcrumb-item">
            <a href="javascript:;">Data Master</a>
         </li>
         <li class="breadcrumb-item active">Mater Pegawai</li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header">Data Master</h1>
      <!-- end page-header -->
      <!-- begin row -->
      <div class="row">
         <!-- begin col-12 -->
         <div class="col-xl-12">
            <!-- begin panel -->
            <panel title="Input Data Master Pegawai" bodyClass="panel-form">
               <div>
                  <vue-good-table
                     ref="table"
                     :columns="columns"
                     :rows="rows"
                     :line-numbers="true"
                     :selectOptions="{
                        enabled: true,
                        selectOnCheckboxOnly: true, // only select when checkbox is clicked instead of the row
                        selectionInfoClass: 'custom-class',
                        selectionText: 'rows selected',
                        clearSelectionText: 'clear',
                        disableSelectInfo: true, // disable the select info panel on top
                     }"
                     :search-options="{
                        enabled: true
                     }"
                  >
                     <div slot="table-actions">
                        <router-link
                           to="/data-master/pegawai/create"
                           class="btn btn-primary btn-sm text-white m-1"
                        >Tambah</router-link>
                        <button
                           type="button"
                           class="btn btn-success btn-sm text-white m-1"
                           @click="edit"
                        >Edit</button>
                        <button
                           type="button"
                           class="btn btn-danger btn-sm m-1"
                           @click="hapusAlert"
                        >Hapus</button>
                     </div>
                  </vue-good-table>
               </div>
            </panel>
            <!-- end panel -->
         </div>
         <!-- end col-12 -->
      </div>
      <!-- end row -->
   </div>
</template>

<script>
export default {
   data() {
      let peg = JSON.parse(localStorage.getItem("pegawai"));
      return {
         columns: [
            {
               label: "NIK",
               field: "nik"
            },
            {
               label: "Nama",
               field: "nama"
            },
            {
               label: "TMP Lahir",
               field: "tmplahir"
            },
            {
               label: "TGL Lahir",
               field: "tgllahir"
            }
         ],
         rows: peg == null ? [] : peg
      };
   },
   methods: {
      hapusAlert() {
         let data = this.$refs["table"].selectedRows;
         if (data.length) {
            // Use sweetalert2
            const options = {
               title: "Yakin",
               text: "akan hapus data ini?",
               icon: "warning",
               confirmButtonColor: "#dee3e2",
               cancelButtonColor: "#00a8cc",
               confirmButtonText: "YA",
               showCancelButton: true,
               cancelButtonText: "TIDAK"
            };
            this.$swal(options).then(result => {
               if (result.value) {
                  let data = this.$refs["table"].selectedRows;
                  data.forEach(element => {
                     this.hapus("pegawai", element.nik);
                  });
                  this.$swal("Data berhasil dihapus");
               }
            });
         }else{
            this.$swal("Pilih satu data");
         }
      },
      hapus(key, needle) {
         let obj = JSON.parse(localStorage.getItem(key));
         obj = obj == null ? [] : obj;

         let peg = [];
         for (var i = 0; i < obj.length; i++) {
            if (obj[i].nik !== needle) {
               peg.push(obj[i]);
            }
         }
         localStorage.setItem("pegawai", JSON.stringify(peg));
         this.rows = JSON.parse(localStorage.getItem("pegawai"));
      },
      edit() {
         let data = this.$refs["table"].selectedRows;
         if (data.length == 1) {
            let nik = '';
            data.forEach(element => {
               nik = element.nik;
            });
            this.$router.push({ name: "pegawai.edit", params: { id: nik } });
         } else {
            this.$swal("Pilih satu data");
         }
      }
   }
};
</script>