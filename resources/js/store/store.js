import actions from './actions';
import getters from './getters';
import mutations from './mutations';

export default {
    state: {
        page_loader: false,
        loading: false,
        errors: [],
        online: false,
        users: [],
        agents: [],
        agent_data: [],
        zone_agents: [],
        deletedUsers: [],
        roles: [],
        permissions: [],
        user_permissions: [],
        products: [],
        product_stat: [],
        product_names: [],
        customers: [],
        sellers: [],
        discount: [],
        sales: [],
        groups: [],
        drawers: [],
        discounts: [],
        clients: [],
        client: [],
        suppliers: [],
        categories: [],
        menu: [],
        subcategories: [],
        brands: [],
        unique_sku: null,
        option_values: [],
        currency: [],
        files: [],
        couriers: [],

        services: [],
        zones: [],
        cities: [],
        charges: [],
        callcentre_performance: [],
        agent_performance: [],
        charts: [],

        // Deleted
        deleted_clients: [],
        transfers: [],
        warehouses: [],
        saleorder: [],
        invoices: [],
        statuses: [],
        single_product: [],
        product_history: [],

        shipments: [],


        // Dashboard
        user_count: null,
        week_sales_count: null,
        sellers_count: null,
        total_sales_count: null,
        clients_count: null,
        onhand: null,
        countries_count: null,
        branches_count: null,

        product_count: null,
        category_count: null,
        brand_count: null,
        weekly_sale: [],
        top_sales: [],
        product_transactions: [],
        table_column: [],
        dispatch_sales: [],
        call_orders: [],
        dispatch: [],

        // Charts
        clients_chart: null,
        sellers_chart: null,
        sales_chart: [],
        delivery_chart: [],
        return_chart: [],

        // Call center
        status_chart: [],
        call_center: [],
        agent_data: [],
        missedcalls: [],
        calls: [],
        callHistory: [],
        realtime: [],
        leads: [],

        // Api
        api_connect: [],
        options: [],
        slider_1: [],
        slider: [],
        cart: [],
        table_rows: [],
        returns: [],
        sale_return: [],

        app_custom: [],
        subscription: [],

        custom_sale: null,
        riders: [],
        branches: [],
        branch_sale: [],
        vendor_performance: [],

        ous: [],
        company: null,

        templates: [],
        sms_template: [],
        mail_template: [],
        features: [],
        plans: [],
        transactions: [],
        automations: [],
/* 
        ou_count: null,
        scheduled_count: null,
        dispatched_count: null,
        delivered_count: null,
        returns_count: null,
        low_stoke: null,
        pending: null,
        commited: null, */

        dashboard_data: [],


        notifications: [],
        apiData: [],

        custom_report: [],

        rows: [],
        bays: [],
        levels: [],
        bins: [],
        bin: [],
        areas: [],


        pods: [],

        // lowStock: null,
        // stock_count: null,
        // bins_count: null,
        // commited_count: null,
        // available_count: null,
        // dispatch_count: null,
        // product_count: null,
        // warehouse_count: null,

        dashboard_data: [],

        addons: [],
        backups: [],

        shopify_products: [],   
        orders: [],
        orders_search: [],
        setup: [],
        
        shopify_stores: [],
        woocommerce_stores: [],
        sheets: [],
        zoneSheets: [],
    },
    getters,
    mutations,
    actions
}
