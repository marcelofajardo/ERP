<div style="width: 100%;padding: 30px 0px 30px;">
  <table border="0" cellpadding="0" cellspacing="0">
    <tbody>
      <tr>
        <td width="50%" valign="top" align="left" style="background-color: #f9f2ef;padding: 20px 30px;">
          <table align="left" valign="top">
            <tbody>
              <tr>
                <td><div style="font-size: 14px;font-weight: bold;color: #000000;padding-bottom: 5px;">ORDER SUMMARY</div></td>
              </tr>
              <tr>
                <td><div style="color: #898989;font-size: 12px;padding-top: 5px;">Order No:</div></td>
                <td><div style="color: #898989;font-size: 12px;font-weight: bold;padding-top: 5px;">{{ $order->order_id }}</div></td>
              </tr>
              <tr>
                <td><div style="color: #898989;font-size: 12px;padding-top: 5px;">Payment :</div></td>
                <td><div style="color: #898989;font-size: 12px;font-weight: bold;padding-top: 5px;">{{ ucwords($order->payment_mode) }}</div></td>
              </tr>
            </tbody>
          </table>
        </td>
         <td width="50%" valign="top" align="right" style="background-color: #f9f2ef;padding: 20px 30px;">
          <table align="left" valign="top">
            <tbody>
              <tr>
                <td><div style="font-size: 14px;font-weight: bold;color: #000000;padding-bottom: 5px;">SHIPPING ADDRESS</div></td>
              </tr>
              <tr>
                <td><div style="color: #898989;font-size: 12px;padding-top: 5px;font-weight: bold;">{{ $order->customer->name }}</div></td>
              </tr>
              <tr>
                <td><div style="color: #898989;font-size: 12px;padding-top: 5px;">{{ 
                  implode(",",[$customer->address,$customer->city,$customer->pincode,$customer->country,$customer->phone]) }}</div></td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</div>