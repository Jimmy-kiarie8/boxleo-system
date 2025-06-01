<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title>Order Exchange</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="handleExchange">
              <v-select
                v-model="selectedDispatched"
                :items="dispatchedOrders"
                label="Select Dispatched Order"
                item-text="id"
                return-object
                outlined
              ></v-select>

              <v-select
                v-model="selectedScheduled"
                :items="scheduledOrders"
                label="Select Scheduled Order"
                item-text="id"
                return-object
                outlined
              ></v-select>

              <v-btn type="submit" color="primary" :loading="isLoading">
                Exchange Orders
              </v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      dispatchedOrders: [],
      scheduledOrders: [],
      selectedDispatched: null,
      selectedScheduled: null,
      isLoading: false
    };
  },
  async created() {
    await this.fetchOrders();
  },
  methods: {
    async fetchOrders() {
      try {
        const [dispatchedResponse, scheduledResponse] = await Promise.all([
          this.$axios.get('/api/orders?status=dispatched'),
          this.$axios.get('/api/orders?status=scheduled')
        ]);
        this.dispatchedOrders = dispatchedResponse.data;
        this.scheduledOrders = scheduledResponse.data;
      } catch (error) {
        console.error('Error fetching orders:', error);
      }
    },
    async handleExchange() {
      this.isLoading = true;
      try {
        await this.$axios.post('/api/orders/exchange', {
          dispatchedId: this.selectedDispatched.id,
          scheduledId: this.selectedScheduled.id
        });
        this.$toast.success('Orders exchanged successfully');
        await this.fetchOrders();
      } catch (error) {
        console.error('Error exchanging orders:', error);
        this.$toast.error('Failed to exchange orders');
      } finally {
        this.isLoading = false;
      }
    }
  }
};
</script>


/**
* Exchange order statuses between Dispatched and Scheduled
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function exchangeOrders(Request $request)
{
   $request->validate([
       'dispatchedId' => 'required|exists:sales,id',
       'scheduledId' => 'required|exists:sales,id'
   ]);

   DB::beginTransaction();

   try {
       // Get the orders
       $dispatchedOrder = Sale::findOrFail($request->dispatchedId);
       $scheduledOrder = Sale::findOrFail($request->scheduledId);

       // Verify statuses
       if ($dispatchedOrder->status !== 'Dispatched' || $scheduledOrder->status !== 'Scheduled') {
           throw new \Exception('Invalid order statuses for exchange');
       }

       // Exchange statuses
       $dispatchedOrder->status = 'Returned';
       $scheduledOrder->status = 'Dispatched';

       $dispatchedOrder->save();
       $scheduledOrder->save();

       DB::commit();

       return response()->json(['message' => 'Orders exchanged successfully']);
   } catch (\Exception $e) {
       DB::rollBack();
       return response()->json(['error' => $e->getMessage()], 400);
   }
}