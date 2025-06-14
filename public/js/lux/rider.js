(window.webpackJsonp=window.webpackJsonp||[]).push([[51],{1103:function(e,t,r){"use strict";r.r(t);var o=r(2);function a(e){return(a="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function n(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,o)}return r}function s(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=a(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var o=r.call(e,t||"default");if("object"!=a(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==a(t)?t:t+""}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var i={data:function(){return{dialog:!1,form:{rate_per_delivery:10}}},created:function(){var e=this;eventBus.$on("openCreateRider",(function(t){e.dialog=!0,e.getZones()}))},methods:{save:function(){var e={model:"rider/riders",data:this.form};this.$store.dispatch("postItems",e).then((function(e){eventBus.$emit("riderEvent")}))},close:function(){this.dialog=!1},getZones:function(){this.$store.dispatch("getItems",{model:"zones",update:"updateZone"})}},computed:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?n(Object(r),!0).forEach((function(t){s(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):n(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},Object(o.b)(["errors","loading","zones"]))},l=r(0),c=Object(l.a)(i,(function(){var e=this,t=e._self._c;return t("v-layout",{attrs:{row:"","justify-center":""}},[t("v-dialog",{attrs:{persistent:"","max-width":"700px"},model:{value:e.dialog,callback:function(t){e.dialog=t},expression:"dialog"}},[t("v-card",[t("v-card-title",[t("span",{staticClass:"headline text-center",staticStyle:{margin:"auto"}},[e._v("Create Rider")])]),e._v(" "),t("v-divider"),e._v(" "),t("v-card-text",[t("v-container",{attrs:{"grid-list-md":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{sm12:""}},[t("div",[t("label",{attrs:{for:""}},[e._v("Rider Name")]),e._v(" "),t("el-input",{attrs:{placeholder:"John Doe"},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}}),e._v(" "),e.errors.name?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.name[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Email Address")]),e._v(" "),t("el-input",{attrs:{placeholder:"john@gmail.com"},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}}),e._v(" "),e.errors.email?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.email[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Phone Number")]),e._v(" "),t("el-input",{attrs:{placeholder:"+1..."},model:{value:e.form.phone,callback:function(t){e.$set(e.form,"phone",t)},expression:"form.phone"}}),e._v(" "),e.errors.phone?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.phone[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Address")]),e._v(" "),t("el-input",{attrs:{placeholder:"Main st"},model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}}),e._v(" "),e.errors.address?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.address[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Rate per delivery")]),e._v(" "),t("el-input",{attrs:{placeholder:"100"},model:{value:e.form.rate_per_delivery,callback:function(t){e.$set(e.form,"rate_per_delivery",t)},expression:"form.rate_per_delivery"}}),e._v(" "),e.errors.rate_per_delivery?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.rate_per_delivery[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Zone")]),e._v(" "),t("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",clearable:"",placeholder:"Select"},model:{value:e.form.zone_id,callback:function(t){e.$set(e.form,"zone_id",t)},expression:"form.zone_id"}},e._l(e.zones,(function(e){return t("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),1)],1)])],1)],1)],1),e._v(" "),t("v-card-actions",[t("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:e.close}},[e._v("Close")]),e._v(" "),t("v-spacer"),e._v(" "),t("v-btn",{attrs:{color:"blue darken-1",text:"",loading:e.loading,disabled:e.loading},on:{click:e.save}},[e._v("Save")])],1)],1)],1)],1)}),[],!1,null,null,null).exports;function d(e){return(d="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function v(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,o)}return r}function u(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=d(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var o=r.call(e,t||"default");if("object"!=d(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==d(t)?t:t+""}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var p={data:function(){return{dialog:!1,form:{}}},created:function(){var e=this;eventBus.$on("openEditRider",(function(t){e.form=t,e.dialog=!0,e.getZones()}))},methods:{save:function(){var e={model:"rider/riders",id:this.form.id,data:this.form};this.$store.dispatch("patchItems",e).then((function(e){eventBus.$emit("riderEvent")}))},close:function(){this.dialog=!1},getZones:function(){this.$store.dispatch("getItems",{model:"zones",update:"updateZone"})}},computed:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?v(Object(r),!0).forEach((function(t){u(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):v(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},Object(o.b)(["errors","loading","zones"]))},m={data:function(){return{dialog:!1,show:!1,loading:!1,form:{password:""},errors:{},rules:{required:function(e){return!!e||"Required."},min:function(e){return e.length>=8||"Min 8 characters"},emailMatch:function(){return"The email and password you entered don't match"}}}},created:function(){var e=this;eventBus.$on("openPasswordEvent",(function(t){e.dialog=!0,e.form.id=t}))},methods:{passwordChange:function(){var e=this,t={data:this.form,id:this.form.id,model:"/rider/rider_password"};this.$store.dispatch("patchItems",t).then((function(t){e.form.password="",e.close()}))},close:function(){this.dialog=!1}}};function f(e){return(f="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function _(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,o)}return r}function b(e,t,r){return(t=function(e){var t=function(e,t){if("object"!=f(e)||!e)return e;var r=e[Symbol.toPrimitive];if(void 0!==r){var o=r.call(e,t||"default");if("object"!=f(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(e,"string");return"symbol"==f(t)?t:t+""}(t))in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}var h={props:["user"],components:{Create:c,Edit:Object(l.a)(p,(function(){var e=this,t=e._self._c;return t("v-layout",{attrs:{row:"","justify-center":""}},[t("v-dialog",{attrs:{persistent:"","max-width":"700px"},model:{value:e.dialog,callback:function(t){e.dialog=t},expression:"dialog"}},[t("v-card",[t("v-card-title",[t("span",{staticClass:"headline text-center",staticStyle:{margin:"auto"}},[e._v("Create Rider")])]),e._v(" "),t("v-divider"),e._v(" "),t("v-card-text",[t("v-container",{attrs:{"grid-list-md":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{sm12:""}},[t("div",[t("label",{attrs:{for:""}},[e._v("Rider Name")]),e._v(" "),t("el-input",{attrs:{placeholder:"John Doe"},model:{value:e.form.name,callback:function(t){e.$set(e.form,"name",t)},expression:"form.name"}}),e._v(" "),e.errors.name?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.name[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Email Address")]),e._v(" "),t("el-input",{attrs:{placeholder:"john@gmail.com"},model:{value:e.form.email,callback:function(t){e.$set(e.form,"email",t)},expression:"form.email"}}),e._v(" "),e.errors.email?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.email[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Phone Number")]),e._v(" "),t("el-input",{attrs:{placeholder:"+1..."},model:{value:e.form.phone,callback:function(t){e.$set(e.form,"phone",t)},expression:"form.phone"}}),e._v(" "),e.errors.phone?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.phone[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Address")]),e._v(" "),t("el-input",{attrs:{placeholder:"Main st"},model:{value:e.form.address,callback:function(t){e.$set(e.form,"address",t)},expression:"form.address"}}),e._v(" "),e.errors.address?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.address[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Rate per delivery")]),e._v(" "),t("el-input",{attrs:{placeholder:"100"},model:{value:e.form.rate_per_delivery,callback:function(t){e.$set(e.form,"rate_per_delivery",t)},expression:"form.rate_per_delivery"}}),e._v(" "),e.errors.rate_per_delivery?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.rate_per_delivery[0]))]):e._e()],1),e._v(" "),t("div",[t("label",{attrs:{for:""}},[e._v("Zone")]),e._v(" "),t("el-select",{staticStyle:{width:"100%"},attrs:{filterable:"",clearable:"",placeholder:"Select"},model:{value:e.form.zone_id,callback:function(t){e.$set(e.form,"zone_id",t)},expression:"form.zone_id"}},e._l(e.zones,(function(e){return t("el-option",{key:e.id,attrs:{label:e.name,value:e.id}})})),1)],1)])],1)],1)],1),e._v(" "),t("v-card-actions",[t("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:e.close}},[e._v("Close")]),e._v(" "),t("v-spacer"),e._v(" "),t("v-btn",{attrs:{color:"blue darken-1",text:"",loading:e.loading,disabled:e.loading},on:{click:e.save}},[e._v("Save")])],1)],1)],1)],1)}),[],!1,null,null,null).exports,myPassword:Object(l.a)(m,(function(){var e=this,t=e._self._c;return t("v-layout",{attrs:{row:"","justify-center":""}},[t("v-dialog",{attrs:{persistent:"","max-width":"500px"},model:{value:e.dialog,callback:function(t){e.dialog=t},expression:"dialog"}},[t("v-card",[t("v-card-title",[t("span",{staticClass:"headline text-center",staticStyle:{margin:"auto"}},[e._v("Change password")])]),e._v(" "),t("v-divider"),e._v(" "),t("v-card-text",[t("v-container",{attrs:{"grid-list-md":""}},[t("v-layout",{attrs:{row:"",wrap:""}},[t("v-flex",{attrs:{sm12:""}},[t("v-card-text",[t("div",[t("v-text-field",{attrs:{"append-icon":e.show?"visibility":"visibility_off",type:e.show?"text":"password",name:"input-10-1",label:"New password",counter:""},on:{"click:append":function(t){e.show=!e.show}},model:{value:e.form.password,callback:function(t){e.$set(e.form,"password",t)},expression:"form.password"}})],1),e._v(" "),e.errors.password?t("small",{staticClass:"has-text-danger"},[e._v(e._s(e.errors.password[0]))]):e._e()])],1)],1)],1)],1),e._v(" "),t("v-card-actions",[t("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:e.close}},[e._v("Close")]),e._v(" "),t("v-spacer"),e._v(" "),t("v-btn",{attrs:{color:"blue darken-1",text:"",loading:e.loading,disabled:e.loading},on:{click:e.passwordChange}},[e._v("Update")])],1)],1)],1)],1)}),[],!1,null,null,null).exports},data:function(){return{form:{},loader:!1,search:"",riders_det:{data:[]},riders_search:[],temp:"",checkedRows:[],headers:[{text:"Id#",value:"id"},{text:"Name",value:"name"},{text:"Email",value:"email"},{text:"Phone No.",value:"phone"},{text:"Created On",value:"created_at"},{text:"Actions",value:"actions",sortable:!1}]}},methods:{openCreate:function(){eventBus.$emit("openCreateRider")},openPassword:function(e){eventBus.$emit("openPasswordEvent",e)},openEdit:function(e){eventBus.$emit("openEditRider",e)},confirm_delete:function(e){var t=this;this.$confirm("This will permanently delete the file. Continue?","Warning",{confirmButtonText:"OK",cancelButtonText:"Cancel",type:"warning"}).then((function(){t.deleteItem(e)})).catch((function(){t.$message({type:"error",message:"Delete canceled"})}))},activatePortal:function(e,t){var r={data:{status:t},id:e,model:"/rider/mobile_account"};this.$store.dispatch("patchItems",r).then((function(e){eventBus.$emit("riderEvent")}))},deleteItem:function(e){var t=this;this.$store.dispatch("deleteItem","riders/"+e.id).then((function(e){t.$message({type:"success",message:"Delete completed"}),t.getRiders()}))},openShow:function(e){eventBus.$emit("openShowRider",e)},getRiders:function(){this.$store.dispatch("getItems",{model:"rider/riders",update:"updateRidersList"})},updateSelected:function(e){var t=this;this.checkedRows.length<1?eventBus.$emit("errorEvent","Please select atleast one row"):this.$store.dispatch("updateSelected",{url:e,checked:this.checkedRows}).then((function(e){eventBus.$emit("alertRequest","Updated"),t.checkedRows=[],t.getRiders()}))},selectionChanged:function(e){this.checkedRows=e.selectedRows},next_page:function(e,t){var r={path:e,page:t,update:"updateRidersList"};this.$store.dispatch("nextPage",r)}},computed:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?_(Object(r),!0).forEach((function(t){b(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):_(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},Object(o.b)(["riders","loading"])),mounted:function(){eventBus.$emit("LoadingEvent"),this.getRiders()},created:function(){var e=this;eventBus.$on("riderEvent",(function(t){e.getRiders()})),eventBus.$on("responseChooseEvent",(function(e){console.log(e),"Excel"==e?eventBus.$emit("openEditRider"):eventBus.$emit("openCreateRider")}))}},g=(r(848),Object(l.a)(h,(function(){var e=this,t=e._self._c;return t("div",[t("v-container",{attrs:{fluid:"","fill-height":""}},[t("v-layout",{attrs:{"justify-center":"","align-center":"",wrap:""}},[t("v-flex",{attrs:{sm12:""}},[t("v-card",{staticStyle:{padding:"20px 0"}},[t("el-breadcrumb",{attrs:{"separator-class":"el-icon-arrow-right"}},[t("el-breadcrumb-item",{attrs:{to:{path:"/"}}},[e._v("Dashboard")]),e._v(" "),t("el-breadcrumb-item",[e._v("Riders")])],1)],1)],1),e._v(" "),t("v-flex",{attrs:{sm12:""}}),e._v(" "),t("v-flex",{attrs:{sm12:""}},[t("v-card",{staticStyle:{padding:"10px 0"}},[t("v-layout",{attrs:{row:""}},[t("v-flex",{staticStyle:{"margin-left":"10px"},attrs:{sm1:""}},[t("v-tooltip",{attrs:{right:""},scopedSlots:e._u([{key:"activator",fn:function(r){var o=r.on;return[t("v-btn",e._g({staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:e.getRiders},slot:"activator"},o),[t("v-icon",{attrs:{color:"blue darken-2",small:""}},[e._v("mdi-refresh")])],1)]}}])},[e._v(" "),t("span",[e._v("Refresh")])])],1),e._v(" "),t("v-flex",{attrs:{sm2:""}},[t("h3",{staticStyle:{"margin-left":"30px !important","margin-top":"10px"}},[e._v("Riders")])]),e._v(" "),t("v-flex",{attrs:{"offset-sm8":"",sm3:""}},[e.user.can["Rider create"]?t("v-btn",{attrs:{color:"info",text:""},on:{click:e.openCreate}},[e._v("New Rider")]):e._e()],1)],1),e._v(" "),e.riders.last_page>1?t("v-pagination",{attrs:{length:e.riders.last_page,"total-visible":"5",circle:""},on:{input:function(t){return e.next_page(e.riders.path,e.riders.current_page)}},model:{value:e.riders.current_page,callback:function(t){e.$set(e.riders,"current_page",t)},expression:"riders.current_page"}}):e._e(),e._v(" "),t("v-card-title",[t("v-text-field",{attrs:{"append-icon":"search",label:"Search","single-line":"","hide-details":""},model:{value:e.search,callback:function(t){e.search=t},expression:"search"}})],1),e._v(" "),t("v-data-table",{attrs:{headers:e.headers,items:e.riders,search:e.search},scopedSlots:e._u([e.user.can["Vendor edit"]?{key:"item.actions",fn:function(r){var o=r.item;return[t("v-tooltip",{attrs:{bottom:""},scopedSlots:e._u([{key:"activator",fn:function(r){var a=r.on;return[t("v-btn",e._g({staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:function(t){return e.openEdit(o)}},slot:"activator"},a),[t("v-icon",{attrs:{small:"",color:"blue darken-2"}},[e._v("mdi-pen")])],1)]}}],null,!0)},[e._v(" "),t("span",[e._v("Edit "+e._s(o.name))])]),e._v(" "),o.portal_active?t("v-tooltip",{attrs:{bottom:""},scopedSlots:e._u([{key:"activator",fn:function(r){var a=r.on;return[t("v-btn",e._g({staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:function(t){return e.deactivatePortal(o.id)}},slot:"activator"},a),[o.portal_active?t("v-icon",{attrs:{small:"",color:"success"}},[e._v("mdi-account-multiple-check-outline")]):e._e()],1)]}}],null,!0)},[e._v(" "),t("span",[e._v("Deactivate account")])]):t("v-tooltip",{attrs:{bottom:""},scopedSlots:e._u([{key:"activator",fn:function(r){var a=r.on;return[t("v-btn",e._g({staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:function(t){return e.activatePortal(o.id)}},slot:"activator"},a),[t("v-icon",{attrs:{small:"",color:"red"}},[e._v("mdi-account-multiple-remove-outline")])],1)]}}],null,!0)},[e._v(" "),t("span",[e._v("Activate account")])]),e._v(" "),e.user.can["User reset password"]&&o.portal_active?t("v-tooltip",{attrs:{bottom:""},scopedSlots:e._u([{key:"activator",fn:function(r){var a=r.on;return[t("v-btn",e._g({staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:function(t){return e.openPassword(o.id)}},slot:"activator"},a),[t("v-icon",{attrs:{small:"",color:"blue darken-2"}},[e._v("mdi-lock")])],1)]}}],null,!0)},[e._v(" "),t("span",[e._v("Change "+e._s(o.name)+"'s' password")])]):e._e(),e._v(" "),e.user.can["Vendor delete"]?t("v-tooltip",{attrs:{bottom:""},scopedSlots:e._u([{key:"activator",fn:function(r){var a=r.on;return[t("v-btn",e._g({staticClass:"mx-0",attrs:{slot:"activator",icon:""},on:{click:function(t){return e.confirm_delete(o)}},slot:"activator"},a),[o.active?t("v-icon",{attrs:{small:"",color:"pink darken-2"}},[e._v("mdi-checkbox")]):t("v-icon",{attrs:{small:"",color:"pink darken-2"}},[e._v("mdi-close-circle")])],1)]}}],null,!0)},[e._v(" "),o.active?t("span",[e._v("Deactivate "+e._s(o.name))]):t("span",[e._v("Activate "+e._s(o.name))])]):e._e()]}}:null],null,!0)})],1)],1)],1)],1),e._v(" "),t("Create"),e._v(" "),t("Edit"),e._v(" "),t("myPassword")],1)}),[],!1,null,"50a5910e",null));t.default=g.exports},653:function(e,t,r){var o=r(849);"string"==typeof o&&(o=[[e.i,o,""]]);var a={hmr:!0,transform:void 0,insertInto:void 0};r(4)(o,a);o.locals&&(e.exports=o.locals)},848:function(e,t,r){"use strict";r(653)},849:function(e,t,r){(e.exports=r(3)(!1)).push([e.i,".el-input--prefix .el-input__inner[data-v-50a5910e]{border-radius:50px!important}.v-toolbar__content[data-v-50a5910e],.v-toolbar__extension[data-v-50a5910e]{height:auto!important}.v-avatar[data-v-50a5910e]{height:10px!important;width:10px!important;margin-left:40%!important}",""])}}]);