<template>
   <div>

      <vs-row>
         <vs-col type="flex" vs-lg="12" vs-xs="12">
            <vs-card>
               <h4 class="card-title d-flex">
                  Form
               </h4>
               <vs-button @click="popupActive=true" color="primary" type="filled">Open Popup</vs-button>
            </vs-card>
         </vs-col>
      </vs-row>

      <vs-popup classContent="popup-example" title="Popup Title" :active.sync="popupActive">
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputText">Text</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-input type="text" class="inputx" v-model="text" id="InputText" />
            </vs-col>
         </vs-row>
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputDate">Date</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-input type="date" class="inputx" v-model="date" id="InputDate" />
            </vs-col>
         </vs-row>
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputCheckBox">CheckBox</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-checkbox class="justify-content-start" v-model="checkBox" id="InputCheckBox">{{checkBox}}
               </vs-checkbox>
            </vs-col>
         </vs-row>
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputSelect">Select</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-select autocomplete v-model="select" id="InputSelect">
                  <vs-select-item :value="item.value" :text="item.text" v-for="item in options" :key="item.text" />
               </vs-select>
            </vs-col>
         </vs-row>
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputNumber">Number</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-input-number v-model="number" id="InputNumber" min="1" max="10" :step="1" />
            </vs-col>
         </vs-row>
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputStatus">Status</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-switch color="success" v-model="status" id="InputStatus">
                  <span slot="on">Active</span>
                  <span slot="off">Not Active</span>
               </vs-switch>
            </vs-col>
         </vs-row>
         <vs-row class="form-group">
            <vs-col vs-w="4" vs-xs="12">
               <label class="vs-input--label" for="InputTextarea">Textarea</label>
            </vs-col>
            <vs-col vs-w="8" vs-xs="12">
               <vs-textarea counter="20" :counter-danger.sync="counterDanger" v-model="textarea" id="InputTextarea" />
            </vs-col>
         </vs-row>
         <vs-divider />
         <div class="d-flex">
            <vs-button class="ml-auto" color="success" type="filled">Save</vs-button>
            <vs-button class="ml-2" color="danger" type="filled">Cancel</vs-button>
         </div>
      </vs-popup>

   </div>
</template>

<script>
   export default {
   name: "form_input",
   data: () => ({
     text: "",
     password: "",
     date: "",
     checkBox: true,
     select: "",
     options: [
       {key: "KIT", value: "Krakatau IT"}
     ],
     number: 5,
     status: true,
     textarea: '',
     counterDanger: false,
     popupActive: false,
   }),
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