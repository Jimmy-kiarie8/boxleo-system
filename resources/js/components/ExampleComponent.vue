<template>
<div>
    <!-- <div class="card">
        <DataTable :value="products" :paginator="true" :rows="5" :filters.sync="filters1"
        :selection.sync="selectedCustomer1" selectionMode="single" dataKey="id"
        stateStorage="session" stateKey="dt-state-demo-session" responsiveLayout="scroll" showGridlines filterDisplay="menu" :loading="loading" :globalFilterFields="['code','description','price','category','quantity']">
            <Column field="code" header="code"></Column>
            <Column field="name" header="name"></Column>
            <Column field="description" header="description"></Column>
            <Column field="price" header="price"></Column>
            <template #paginatorstart>
                <Button type="button" icon="pi pi-refresh" class="p-button-text" />
            </template>
            <template #paginatorend>
                <Button type="button" icon="pi pi-cloud" class="p-button-text" />
            </template>
        </DataTable>
    </div> -->

    <div class="card">
        <DataTable :value="products" :paginator="true" class="p-datatable-customers" :rows="4" dataKey="id" :filters.sync="filters2" filterDisplay="row" :loading="loading" responsiveLayout="scroll" :globalFilterFields="['code','description','price','category','quantity']">
            <!-- <template #header>
            <div class="flex justify-content-end">
                <span class="p-input-icon-left ">
                    <i class="pi pi-search" />
                    <InputText v-model="filters2['global'].value" placeholder="Keyword Search" />
                </span>
            </div>
        </template> -->
            <template #empty>
                No customers found.
            </template>
            <template #loading>
                Loading customers data. Please wait.
            </template>
            <Column field="name" header="Name" :styles="{'min-width':'12rem'}">
                <template #body="{data}">
                    {{data.name}}
                </template>
                <template #filter="{filterModel,filterCallback}">
                    <InputText type="text" v-model="filterModel.value" @keydown.enter="filterCallback()" class="p-column-filter" :placeholder="`Search by name - ${filterModel.matchMode}`" v-tooltip.top.focus="'Hit enter key to filter'" />
                </template>
            </Column>
            <Column header="Country" filterField="country.name" :styles="{'min-width':'12rem'}">
                <template #body="{data}">
                    <!-- <img src="../../assets/images/flag_placeholder.png" :class="'flag flag-' + data.country.code" width="30" /> -->
                    <span class="image-text">{{data.country.name}}</span>
                </template>
                <template #filter="{filterModel,filterCallback}">
                    <InputText type="text" v-model="filterModel.value" @input="filterCallback()" class="p-column-filter" placeholder="Search by country" v-tooltip.top.focus="'Filter as you type'" />
                </template>
            </Column>
            <Column header="Agent" filterField="representative" :showFilterMenu="false" :styles="{'min-width':'14rem'}">
                <template #body="{data}">
                    <img :alt="data.representative.name" :src="'demo/images/avatar/' + data.representative.image" width="32" style="vertical-align: middle" />
                    <span class="image-text">{{data.representative.name}}</span>
                </template>
                <template #filter="{filterModel,filterCallback}">
                    <MultiSelect v-model="filterModel.value" @change="filterCallback()" :options="representatives" optionLabel="name" placeholder="Any" class="p-column-filter">
                        <template #option="slotProps">
                            <div class="p-multiselect-representative-option">
                                <img :alt="slotProps.option.name" :src="'demo/images/avatar/' + slotProps.option.image" width="32" style="vertical-align: middle" />
                                <span class="image-text">{{slotProps.option.name}}</span>
                            </div>
                        </template>
                    </MultiSelect>
                </template>
            </Column>
            <Column field="status" header="Status" :showFilterMenu="false" :styles="{'min-width':'12rem'}">
                <template #body="{data}">
                    <span :class="'customer-badge status-' + data.status">{{data.status}}</span>
                </template>
                <template #filter="{filterModel,filterCallback}">
                    <Dropdown v-model="filterModel.value" @change="filterCallback()" :options="statuses" placeholder="Any" class="p-column-filter" :showClear="true">
                        <template #value="slotProps">
                            <span :class="'customer-badge status-' + slotProps.value" v-if="slotProps.value">{{slotProps.value}}</span>
                            <span v-else>{{slotProps.placeholder}}</span>
                        </template>
                        <template #option="slotProps">
                            <span :class="'customer-badge status-' + slotProps.option">{{slotProps.option}}</span>
                        </template>
                    </Dropdown>
                </template>
            </Column>
            <Column field="verified" header="Verified" dataType="boolean" :styles="{'min-width':'6rem'}">
                <template #body="{data}">
                    <i class="pi" :class="{'true-icon pi-check-circle': data.verified, 'false-icon pi-times-circle': !data.verified}"></i>
                </template>
                <template #filter="{filterModel,filterCallback}">
                    <TriStateCheckbox v-model="filterModel.value" @change="filterCallback()" />
                </template>
            </Column>
        </DataTable>
    </div>

</div>
</template>

<script>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
export default {
    components: {
        DataTable,
        Column
    },
    data() {
        return {
            products: [{
                    "id": "1000",
                    "code": "f230fh0g3",
                    "name": "Bamboo Watch",
                    "description": "Product Description",
                    "image": "bamboo-watch.jpg",
                    "price": 65,
                    "category": "Accessories",
                    "quantity": 24,
                    "inventoryStatus": "INSTOCK",
                    "rating": 5
                },
                {
                    "id": "1001",
                    "code": "nvklal433",
                    "name": "Black Watch",
                    "description": "Product Description",
                    "image": "black-watch.jpg",
                    "price": 72,
                    "category": "Accessories",
                    "quantity": 61,
                    "inventoryStatus": "INSTOCK",
                    "rating": 4
                },
                {
                    "id": "1002",
                    "code": "zz21cz3c1",
                    "name": "Blue Band",
                    "description": "Product Description",
                    "image": "blue-band.jpg",
                    "price": 79,
                    "category": "Fitness",
                    "quantity": 2,
                    "inventoryStatus": "LOWSTOCK",
                    "rating": 3
                },
                {
                    "id": "1003",
                    "code": "244wgerg2",
                    "name": "Blue T-Shirt",
                    "description": "Product Description",
                    "image": "blue-t-shirt.jpg",
                    "price": 29,
                    "category": "Clothing",
                    "quantity": 25,
                    "inventoryStatus": "INSTOCK",
                    "rating": 5
                },
                {
                    "id": "1004",
                    "code": "h456wer53",
                    "name": "Bracelet",
                    "description": "Product Description",
                    "image": "bracelet.jpg",
                    "price": 15,
                    "category": "Accessories",
                    "quantity": 73,
                    "inventoryStatus": "INSTOCK",
                    "rating": 4
                },
                {
                    "id": "1005",
                    "code": "av2231fwg",
                    "name": "Brown Purse",
                    "description": "Product Description",
                    "image": "brown-purse.jpg",
                    "price": 120,
                    "category": "Accessories",
                    "quantity": 0,
                    "inventoryStatus": "OUTOFSTOCK",
                    "rating": 4
                },
                {
                    "id": "1006",
                    "code": "bib36pfvm",
                    "name": "Chakra Bracelet",
                    "description": "Product Description",
                    "image": "chakra-bracelet.jpg",
                    "price": 32,
                    "category": "Accessories",
                    "quantity": 5,
                    "inventoryStatus": "LOWSTOCK",
                    "rating": 3
                },
                {
                    "id": "1007",
                    "code": "mbvjkgip5",
                    "name": "Galaxy Earrings",
                    "description": "Product Description",
                    "image": "galaxy-earrings.jpg",
                    "price": 34,
                    "category": "Accessories",
                    "quantity": 23,
                    "inventoryStatus": "INSTOCK",
                    "rating": 5
                },
                {
                    "id": "1008",
                    "code": "vbb124btr",
                    "name": "Game Controller",
                    "description": "Product Description",
                    "image": "game-controller.jpg",
                    "price": 99,
                    "category": "Electronics",
                    "quantity": 2,
                    "inventoryStatus": "LOWSTOCK",
                    "rating": 4
                },
                {
                    "id": "1009",
                    "code": "cm230f032",
                    "name": "Gaming Set",
                    "description": "Product Description",
                    "image": "gaming-set.jpg",
                    "price": 299,
                    "category": "Electronics",
                    "quantity": 63,
                    "inventoryStatus": "INSTOCK",
                    "rating": 3
                }
            ],

            loading: false,
            selectedCustomer1: null,
            selectedCustomer2: null,
            filters1: {},
            filters2: {},
            representatives: [{
                    name: "Amy Elsner",
                    image: 'amyelsner.png'
                },
                {
                    name: "Anna Fali",
                    image: 'annafali.png'
                },
                {
                    name: "Asiya Javayant",
                    image: 'asiyajavayant.png'
                },
                {
                    name: "Bernardo Dominic",
                    image: 'bernardodominic.png'
                },
                {
                    name: "Elwin Sharvill",
                    image: 'elwinsharvill.png'
                },
                {
                    name: "Ioni Bowcher",
                    image: 'ionibowcher.png'
                },
                {
                    name: "Ivan Magalhaes",
                    image: 'ivanmagalhaes.png'
                },
                {
                    name: "Onyama Limba",
                    image: 'onyamalimba.png'
                },
                {
                    name: "Stephen Shaw",
                    image: 'stephenshaw.png'
                },
                {
                    name: "XuXue Feng",
                    image: 'xuxuefeng.png'
                }
            ],
            statuses: [
                'unqualified', 'qualified', 'new', 'negotiation', 'renewal', 'proposal'
            ]
        }
    },
    productService: null,
    created() {
        // this.productService = new ProductService();
    },
    mounted() {
        // this.productService.getProductsSmall().then(data => this.products = data);
    },

    methods: {
        filterDate(value, filter) {
            if (filter === undefined || filter === null || (typeof filter === 'string' && filter.trim() === '')) {
                return true;
            }

            if (value === undefined || value === null) {
                return false;
            }

            return value === this.formatDate(filter);
        },
        formatDate(date) {
            let month = date.getMonth() + 1;
            let day = date.getDate();

            if (month < 10) {
                month = '0' + month;
            }

            if (day < 10) {
                day = '0' + day;
            }

            return date.getFullYear() + '-' + month + '-' + day;
        }
    }
}
</script>
