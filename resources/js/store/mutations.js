export default {
    page_loader(state, payload) {
        state.page_loader = payload
    },
    loading(state, payload) {
        state.loading = payload
    },
    errors(state, payload) {
        state.errors = payload
    },
    alertEvent(state, payload) {
        state.alertEvent = payload
    },
    onlineStatus(state, payload) {
        state.online = payload
    },
    updateUsersList(state, payload) {
        state.users = payload
    },
    updateAgentList(state, payload) {
        state.agents = payload
    },
    updateZoneAgent(state, payload) {
        state.zone_agents = payload
    },
    updateAgentData(state, payload) {
        state.agent_data = payload
    },
    updateDeletedUsersList(state, payload) {
        state.deletedUsers = payload
    },
    updateRoleList(state, payload) {
        state.roles = payload
    },
    updateCouriersList(state, payload) {
        state.couriers = payload
    },
    updatePermissionList(state, payload) {
        state.permissions = payload
    },
    updateUserPermission(state, payload) {
        state.user_permissions = payload
    },
    updateProductsList(state, payload) {
        state.products = payload
    },
    updateProductName(state, payload) {
        state.product_names = payload
    },
    updateSupplierList(state, payload) {
        state.suppliers = payload
    },
    updateClientList(state, payload) {
        state.clients = payload
    },
    updateClient(state, payload) {
        state.client = payload
    },
    updateSellerList(state, payload) {
        state.sellers = payload
    },
    updateSaleList(state, payload) {
        state.sales = payload
    },
    updateSale(state, payload) {
        state.saleorder = payload
    },
    // Unique
    updateunique_sku(state, payload) {
        state.unique_sku = payload
    },
    updateunique_inv(state, payload) {
        state.unique_inv = payload
    },
    updateunique_pkg(state, payload) {
        state.unique_pkg = payload
    },

    updateDiscountList(state, payload) {
        state.discounts = payload
    },
    updateGroupList(state, payload) {
        state.groups = payload
    },
    updateDrawerList(state, payload) {
        state.drawers = payload
    },


    updateCharges(state, payload) {
        state.charges = payload
    },


    updateReturnsList(state, payload) {
        state.returns = payload
    },

    updateSaleReturn(state, payload) {
        state.sale_return = payload
    },

    updateCategoryList(state, payload) {
        state.categories = payload
    },

    updateService(state, payload) {
        state.services = payload
    },
    updateZone(state, payload) {
        state.zones = payload
    },
    updateCity(state, payload) {
        state.cities = payload
    },


    updateBrandList(state, payload) {
        state.brands = payload
    },

    // Deleted
    updateDeletedClients(state, payload) {
        state.deleted_clients = payload
    },

    updateOptionsList(state, payload) {
        state.options = payload
    },
    updateOptionValuesList(state, payload) {
        state.option_values = payload
    },
    updateSubcategoryList(state, payload) {
        state.subcategories = payload
    },
    updateMenuList(state, payload) {
        state.menu = payload
    },
    updateCurrencyList(state, payload) {
        state.currency = payload
    },
    updateSliderList(state, payload) {
        state.slider = payload
    },

    updateWeeklySalesList(state, payload) {
        state.weekly_sale = payload
    },


    updateCallcentrePerformance(state, payload) {
        state.callcentre_performance = payload
    },

    updateAgentPerformance(state, payload) {
        state.agent_performance = payload
    },

    updateTopSaleList(state, payload) {
        state.top_sales = payload
    },
    updateLowstokeList(state, payload) {
        state.low_stoke = payload
    },
    updateTransactionsList(state, payload) {
        state.product_transactions = payload
    },

    updateColumnList(state, payload) {
        state.table_column = payload
    },

    updateInvoiceList(state, payload) {
        state.invoices = payload
    },
    updatePackagesList(state, payload) {
        state.packages = payload
    },
    updateStatusList(state, payload) {
        state.statuses = payload
    },
    updateProduct(state, payload) {
        state.single_product = payload
    },
    updateProductStat(state, payload) {
        state.product_stat = payload
    },

    updateFilterList(state, payload) {
        state.custom_sale = payload
    },

    updateAppCustomList(state, payload) {
        state.app_custom = payload
    },
    updateAutomations(state, payload) {
        state.automations = payload
    },
    updateSubscriptionList(state, payload) {
        state.subscription = payload
    },

    updateTableRows(state, payload) {
        state.table_rows = payload
    },
/* 
    updateUserCountList(state, payload) {
        state.user_count = payload
    },
    updateWeekSalesCountList(state, payload) {
        state.week_sales_count = payload
    },
    updateSellerCountList(state, payload) {
        state.sellers_count = payload
    },
    updateSaleCountList(state, payload) {
        state.total_sales_count = payload
    },
    UpdateProductsCountList(state, payload) {
        state.product_count = payload
    },
    UpdateCategoryCountList(state, payload) {
        state.category_count = payload
    },
    UpdateBrandCountList(state, payload) {
        state.brand_count = payload
    },

    updateClientCountList(state, payload) {
        state.clients_count = payload
    },
 */

    updateClientChartList(state, payload) {
        state.clients_chart = payload
    },
    updateSellerChartList(state, payload) {
        state.sellers_chart = payload
    },
    updateSaleChartList(state, payload) {
        state.sales_chart = payload
    },
    updateDeliveryChart(state, payload) {
        state.delivery_chart = payload
    },
    updateReturnChart(state, payload) {
        state.return_chart = payload
    },

    // Call center
    updateStatusChart(state, payload) {
        state.status_chart = payload
    },    
    updateCallDashboard(state, payload) {
        state.call_center = payload
    }, 
    updateAgentData(state, payload) {
        state.agent_data = payload
    },
    updateMissed(state, payload) {
        state.missedcalls = payload
    },
    updateCalls(state, payload) {
        state.calls = payload
    },
    updateAgentChart(state, payload) {
        state.charts = payload
    },
    searchLead(state, payload) {
        state.callHistory = payload
    },
    updateRealtime(state, payload) {
        state.realtime = payload
    },
    updateLead(state, payload) {
        state.leads = payload
    },
    // UpdateUserStatus(state, payload) {
    //     console.log(payload);

    //     Object.assign(state.realtime[payload.index], payload.data);
    //     // Other ways to update
    //     // this.Items[payload.index].text = "CHANGED";
    //     // Vue.set(this.Items, payload.index, { text: "CHANGED" });
    // },

    UpdateUserStatus(state, payload) {

        const { data, index } = payload;
        state.realtime[index] = data; // Update the user data at the specified index
      },

    updateDashboard(state, payload) {
        state.dashboard_data = payload
    },

    updateProductHistory(state, payload) {
        state.product_history = payload
    },
    updateWarehouseList(state, payload) {
        state.warehouses = payload
    },
    updateTransfers(state, payload) {
        state.transfers = payload
    },
    updateShipments(state, payload) {
        state.shipments = payload
    },

    updateBranchesList(state, payload) {
        state.branches = payload
    },
    updateRidersList(state, payload) {
        state.riders = payload
    },
    updateBranchSaleList(state, payload) {
        state.branch_sale = payload
    },


    updateTemplates(state, payload) {
        state.templates = payload
    },

    updateSmsTemplate(state, payload) {
        state.sms_template = payload
    },

    updateMailTemplate(state, payload) {
        state.mail_template = payload
    },
    updateFeature(state, payload) {
        state.features = payload
    },
    updatePlan(state, payload) {
        state.plans = payload
    },




    updateCompany(state, payload) {
        state.company = payload
    },
    updateOu(state, payload) {
        state.ous = payload
    },

    updateCartList(state, payload) {
        state.cart = payload

        var exists = state.cart.some(function (product_1) {
            return product_1.id === payload.id
        });
        if (!exists) {
            payload.ordered = 1
            state.cart.push(payload)
        } else {
            var index = state.cart.indexOf(payload);
            var add_item = (Object.assign({}, payload));
            add_item.ordered += 1
            // console.log(add_item);
            Object.assign(state.cart[index], add_item)
        }
        state.cart = state.cart
    },



    // Dashboard
    updateScheduled(state, payload) {
        state.scheduled_count = payload
    },
    updateDispatched(state, payload) {
        state.dispatched_count = payload
    },
    updateDelivered(state, payload) {
        state.delivered_count = payload
    },
    updatereturns(state, payload) {
        state.returns_count = payload
    },
    updateLowstock(state, payload) {
        state.low_stoke = payload
    },
    updatePending(state, payload) {
        state.pending = payload
    },
    updateCommited(state, payload) {
        state.commited = payload
    },
    updateOnhand(state, payload) {
        state.onhand = payload
    },
    updateVendorPerformance(state, payload) {
        state.vendor_performance = payload
    },
    updateOuCount(state, payload) {
        state.countries_count = payload
    },
    updateBranchCount(state, payload) {
        state.branches_count = payload
    },
    
    updateTransactions(state, payload) {
        state.transactions = payload
    },

    UpdateSalestatus(state, payload) {
        console.log(payload);

        Object.assign(state.sales.sales.data[payload.index], payload.data);
        // Other ways to update
        // this.Items[payload.index].text = "CHANGED";
        // Vue.set(this.Items, payload.index, { text: "CHANGED" });
    },



    updateNotification(state, payload) {
        state.notifications = payload
    },

    updateDispatchList(state, payload) {
        state.dispatch_sales = payload
    },

    updatApi(state, payload) {
        state.apiData = payload
    },
    updatApiConnection(state, payload) {
        state.api_connect = payload
    },

    updateDispatches(state, payload) {
        state.dispatch = payload
    },

    updateNotificationEvent(state, payload) {

        // state.notifications = payload.data
        state.notifications.count += 1;
        state.notifications.notifications.unshift(payload);
    },

    updateCustomReport(state, payload) {
        state.custom_report = payload
    },

    ResetItem(state, payload) {
        state.item = payload.item
        // state.item = []
    },

    UpdateEmpty(state, payload) {

    },


    updateFiles (state, payload) {
        state.files = payload
    },
    
    updateAreas (state, payload) {
        state.areas = payload
    },
    updateRows (state, payload) {
        state.rows = payload
    },

    updateBays (state, payload) {
        state.bays = payload
    },

    updateLevels (state, payload) {
        state.levels = payload
    },

    updateBins (state, payload) {
        state.bins = payload
    },
    updateBin (state, payload) {
        state.bin = payload
    },


    updatePod (state, payload) {
        state.pods = payload
    },

/* 
    updateLowstock(state, payload) {
        state.lowStock = payload
    },
    updateStockCount(state, payload) {
        state.stock_count = payload
    },
    updateBinCount(state, payload) {
        state.bins_count = payload
    },
    updateCommitedCount(state, payload) {
        state.commited_count = payload
    },
    updateAvailable(state, payload) {
        state.available_count = payload
    },
    updateDispatchCount(state, payload) {
        state.dispatch_count = payload
    },
    UpdateProductsCountList(state, payload) {
        state.product_count = payload
    },
    updateWarehouseCount(state, payload) {
        state.warehouse_count = payload
    },
    updateOuCount(state, payload) {
        state.ou_count = payload
    }, */
    UpdateDashboard(state, payload) {
        state.dashboard_data = payload
    },
    

    
    updateAddons (state, payload) {
        state.addons = payload
    },
    
    updateBackup (state, payload) {
        state.backups = payload
    },

    
    updateShopifyProducts(state, payload) {
        state.shopify_products = payload
    },
    updateOrders(state, payload) {
        state.orders = payload
    },
    orderCallList(state, payload) {
        state.call_orders = payload
    },
    updateOrderSearch(state, payload) {
        state.orders_search = payload
    },
    updateSetup(state, payload) {
        state.setup = payload
    },
    
    updateShopifyStore(state, payload) {
        state.shopify_stores = payload
    },
    updateWoocommerceStore(state, payload) {
        state.woocommerce_stores = payload
    },
    updateSheet(state, payload) {
        state.sheets = payload
    },
    updatezoneSheets(state, payload) {
        state.zoneSheets = payload
    },

}

