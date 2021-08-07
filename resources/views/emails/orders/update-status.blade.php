<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <!-- <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0" /> -->
  <title>Your order has been updated</title>
  <style type="text/css">
    * {box-sizing:border-box; -moz-box-sizing:border-box; -webkit-box-sizing:border-box;}
    body {font-family: arial; font-size: 14px; color: #000000; margin: 0; padding: 0;}
    table {border-collapse: collapse;width: 100%;}
  </style>
</head>
<body>
   <div style="width: 800px; margin: 30px auto; border:2px solid #f4e7e1;">
      <div style="width: 100%;text-align: center; padding-top: 30px;background-color: #f4e7e1;">
        <img src="{{ asset('images/emails/logo.png') }}" alt="" />
      </div>
      <div style="width: 100%;background-color: #f4e7e1;padding: 0 30px;">
        <table>
          <tbody>
            <tr>
              <td>
                <h1>You're all sorted.</h1>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div style="width: 100%; padding: 30px;">
        <table border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <tr>
              <td>
                <h3 style="line-height: 1.24;font-size: 17px;font-weight: bold;letter-spacing: -0.1px;color:#898989;margin: 0;padding: 0;">Hello {{ $customer->name }}</h3>
              </td>
            </tr>
            <tr><td><div style="font-size: 13px;line-height: 1.62;color:#898989;">Your order {{ $order->order_id }} has been updated to {{ $order->order_status }}.</div></td></tr>
          </tbody>
        </table>
     </div>   
   </div>
</body>
</html>
