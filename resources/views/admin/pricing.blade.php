<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pricing</title>
</head>
<body>

    {{-- <div id="paypal-button-container"></div>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AdqfQ_xn0HyksbTB2lY_BZSTfgIxnsVZ2O6ZhItIt_S3el1B23iWyOagUFS4Ikrd4Maj-GYDGJvKYRju&vault=true&intent=subscription"
        data-sdk-integration-source="button-factory"></script>
    <script>
        paypal.Buttons({
          style: {
              shape: 'pill',
              color: 'silver',
              layout: 'vertical',
              label: 'subscribe'
          },
          createSubscription: function(data, actions) {
            return actions.subscription.create({
              'plan_id': 'P-4X0593074X9260716L7XWMWI'
            });
          },
          onApprove: function(data, actions) {
            alert(data.subscriptionID);
          }
      }).render('#paypal-button-container');
    </script> --}}


    <div id="paypal-button-container"></div>
    <script src="https://www.paypal.com/sdk/js?client-id=AdqfQ_xn0HyksbTB2lY_BZSTfgIxnsVZ2O6ZhItIt_S3el1B23iWyOagUFS4Ikrd4Maj-GYDGJvKYRju&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
    <script>
      paypal.Buttons({
          style: {
              shape: 'pill',
              color: 'silver',
              layout: 'vertical',
              label: 'subscribe'
          },
          createSubscription: function(data, actions) {
            return actions.subscription.create({
              'plan_id': 'P-2KN279916F311591HMAD5QQQ'
            });
          },
          onApprove: function(data, actions) {
            alert(data.subscriptionID);
          }
      }).render('#paypal-button-container');
    </script>


</body>
</html>
