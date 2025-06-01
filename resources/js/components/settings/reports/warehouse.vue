<template>
  <v-container>
    <v-row>
      <v-col cols="12" sm="6">
        <v-select v-model="selectedReport" :items="reportTypes" label="Select Report Type"
          @change="clearReport"></v-select>
      </v-col>
      <v-col cols="12" sm="6">
        <!-- <v-select v-model="selectedProducts" :items="products" label="Select Products" multiple></v-select> -->

        <el-select v-model="selectedProducts" filterable remote reserve-keyword placeholder="type at least 3 characters"
          :remote-method="getProduct" :loading="loading" style="width: 100%;" value-key="id" clearable>
          <el-option v-for="item in products.data" :key="item.id" :label="item.product_name" :value="item">
          </el-option>
        </el-select>
      </v-col>
      <v-col cols="12">
        <v-btn @click="fetchReport">Filter</v-btn>
      </v-col>
    </v-row>

    <v-row v-if="selectedReport === 'Daily Product Status Report'">
      <v-col cols="12" sm="6">
        <v-text-field label="Select Date" v-model="dailyStatusDate" type="date"></v-text-field>
      </v-col>
      <v-col cols="12">
        <v-data-table :loading="loading" :headers="dailyStatusHeaders" :items="dailyStatusReport"></v-data-table>
      </v-col>
    </v-row>

    <v-row v-if="selectedReport === 'Product Sales Report'">
      <v-col cols="12" sm="6">
        <v-text-field label="Start Date" v-model="salesStartDate" type="date"></v-text-field>
      </v-col>
      <v-col cols="12" sm="6">
        <v-text-field label="End Date" v-model="salesEndDate" type="date"></v-text-field>
      </v-col>
      <v-col cols="12">
        <v-data-table :loading="loading" :headers="salesHeaders" :items="salesReport"></v-data-table>
      </v-col>
    </v-row>

    <v-row v-if="selectedReport === 'Monthly Product Movement Report'">
      <v-col cols="12" sm="6">
        <v-text-field label="Year" v-model="movementYear" type="number"></v-text-field>
      </v-col>
      <v-col cols="12" sm="6">
        <v-text-field label="Month" v-model="movementMonth" type="number"></v-text-field>
      </v-col>
      <v-col cols="12">
        <v-data-table :loading="loading" :headers="movementHeaders" :items="movementReport"></v-data-table>
      </v-col>
    </v-row>

    <v-row v-if="selectedReport === 'Product Availability Report'">
      <v-col cols="12">
        <v-data-table :loading="loading" :headers="availabilityHeaders" :items="availabilityReport"></v-data-table>
      </v-col>
    </v-row>

    <v-row v-if="selectedReport === 'Product Performance Report'">
      <v-col cols="12" sm="6">
        <v-text-field label="Start Date" v-model="performanceStartDate" type="date"></v-text-field>
      </v-col>
      <v-col cols="12" sm="6">
        <v-text-field label="End Date" v-model="performanceEndDate" type="date"></v-text-field>
      </v-col>
      <v-col cols="12">
        <v-data-table :loading="loading" :headers="performanceHeaders" :items="performanceReport"></v-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapState } from 'vuex';

export default {
  data() {
    return {
      form: {},
      loading: false,
      selectedReport: '',
      selectedProducts: [],
      dailyStatusDate: '',
      salesStartDate: '',
      salesEndDate: '',
      movementYear: '',
      movementMonth: '',
      performanceStartDate: '',
      performanceEndDate: '',
      dailyStatusReport: [],
      salesReport: [],
      movementReport: [],
      availabilityReport: [],
      performanceReport: [],
      reportTypes: [
        'Daily Product Status Report',
        'Product Sales Report',
        'Monthly Product Movement Report',
        // 'Product Availability Report',
        'Product Performance Report'
      ],
      dailyStatusHeaders: [
        { text: 'Product', value: 'product.product_name' },
        { text: 'Delivered', value: 'delivered' },
        { text: 'Dispatched', value: 'dispatched' },
        { text: 'Scheduled', value: 'scheduled' },
        { text: 'Uploaded', value: 'uploaded' },
        { text: 'Returned', value: 'returned' },
        { text: 'Pending', value: 'pending' },
        { text: 'Closing Count', value: 'closing_count' },
        { text: 'Starting Count', value: 'starting_count' },
      ],
      salesHeaders: [
        { text: 'Product Name', value: 'product_name' },
        { text: 'Total Sales', value: 'total_sales' },
        { text: 'Total Quantity Sold', value: 'total_quantity_sold' },
        { text: 'Revenue Generated', value: 'revenue_generated' },
      ],
      movementHeaders: [
        { text: 'Product', value: 'product_name' },
        { text: 'Total Delivered', value: 'total_delivered' },
        { text: 'Total Dispatched', value: 'total_dispatched' },
        { text: 'Total Returned', value: 'total_returned' },
        { text: 'Total Scheduled', value: 'total_scheduled' },
      ],
      availabilityHeaders: [
        // { text: 'Product ID', value: 'product_id' },
        { text: 'Product Name', value: 'product_name' },
        { text: 'Total Quantity', value: 'total_quantity' },
        { text: 'Bin Locations', value: 'bin_locations' },
      ],
      performanceHeaders: [
        // { text: 'Product ID', value: 'product_id' },
        { text: 'Product Name', value: 'product_name' },
        { text: 'Total Sales', value: 'total_sales' },
        { text: 'Total Returns', value: 'total_returns' },
        { text: 'Average Daily Sales', value: 'average_daily_sales' },
        { text: 'Current Stock', value: 'current_stock' },
      ],
    };
  },
  methods: {
    fetchReport() {
      switch (this.selectedReport) {
        case 'Daily Product Status Report':
          this.fetchDailyProductStatusReport();
          break;
        case 'Product Sales Report':
          this.fetchProductSalesReport();
          break;
        case 'Monthly Product Movement Report':
          this.fetchMonthlyProductMovementReport();
          break;
        case 'Product Availability Report':
          this.fetchProductAvailabilityReport();
          break;
        case 'Product Performance Report':
          this.fetchProductPerformanceReport();
          break;
      }
    },
    clearReport() {
      this.dailyStatusReport = [];
      this.salesReport = [];
      this.movementReport = [];
      this.availabilityReport = [];
      this.performanceReport = [];
    },
    fetchDailyProductStatusReport() {
      this.loading = true;
      axios
        .get(`/reports/daily-product-status`, {
          params: {
            date: this.dailyStatusDate,
            products: this.selectedProducts,
          },
        })
        .then((response) => {
          this.loading = false;
          this.dailyStatusReport = response.data;
        })
        .catch((error) => {
          this.loading = false;
          console.error(error);
        });
    },
    fetchProductSalesReport() {
      this.loading = true;
      axios
        .get(`/reports/product-sales`, {
          params: {
            start_date: this.salesStartDate,
            end_date: this.salesEndDate,
            products: this.selectedProducts,
          },
        })
        .then((response) => {
          this.loading = false;
          this.salesReport = response.data;
        })
        .catch((error) => {
          this.loading = false;
          console.error(error);
        });
    },
    fetchMonthlyProductMovementReport() {
      this.loading = true;
      axios
        .get(`/reports/monthly-product-movement`, {
          params: {
            year: this.movementYear,
            month: this.movementMonth,
            products: this.selectedProducts,
          },
        })
        .then((response) => {
          this.loading = false;
          this.movementReport = response.data;
        })
        .catch((error) => {
          this.loading = false;
          console.error(error);
        });
    },
    fetchProductAvailabilityReport() {
      this.loading = true;
      axios
        .get(`/reports/product-availability`, {
          params: {
            products: this.selectedProducts,
          },
        })
        .then((response) => {
          this.loading = false;
          this.availabilityReport = response.data;
        })
        .catch((error) => {
          this.loading = false;
          console.error(error);
        });
    },
    fetchProductPerformanceReport() {
      this.loading = true;
      axios
        .get(`/reports/product-performance`, {
          params: {
            start_date: this.performanceStartDate,
            end_date: this.performanceEndDate,
            products: this.selectedProducts,
          },
        })
        .then((response) => {
          this.loading = false;
          this.performanceReport = response.data;
        })
        .catch((error) => {
          this.loading = false;
          console.error(error);
        });
    },


    getProduct(search) {
      // console.log(search);
      if (search.length > 2) {
        var payload = {
          model: 'product_filter_search',
          update: 'updateProductsList',
          data: {
            search: search,
            vendors: this.form.client
          }
        }
        this.$store.dispatch("filterItems", payload);
      }
    },
    fetchProducts() {

      const payload = {
        model: 'products',
        update: 'updateProductsList'
      }

      this.$store.dispatch("getItems", payload);
    },
  },
  mounted() {
    this.fetchProducts();
    this.fetchProductAvailabilityReport(); // Fetch availability report on mount as it doesn't require date filters
  },
  computed: {
    ...mapState(['products'])
  }
};
</script>
