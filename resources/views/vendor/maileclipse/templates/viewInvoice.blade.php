<!DOCTYPE html>
<html>
<head>
	<title>Invoice of {{ $orders->order_id }}</title>
	<style>
	.bl {
			border-left: 1px solid #000;
		}
		.br {
			border-right: 1px solid #000;
		}
		.bt {
			border-top: 1px solid #000;
		}
		.bb {
			border-bottom: 1px solid #000;
		}
		.bg {
			background: #808080
		}
		.vt {
			vertical-align: top;
		}
		.vm {
			vertical-align: middle;
		}
		.vb {
			vertical-align: bottom;
		}
#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
  font-size: 12px;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 5px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers th {
  padding-top: 5px;
  padding-bottom: 5px;
  text-align: left;
  background-color: #808080;
  color: white;
}
</style>
</head>
<body>
<table id="customers">
<tbody>
<tr>
<th colspan="3">COMMERCIAL INVOICE</th>
</tr>
<tr>
<th>Shipper/Exporter of Record</th>
<th>&nbsp;</th>
<th>SHIPMENT ORDER</th>
</tr>
<tr>
<td>LUXURY UNLIMITED</td>
<td style="width: 30%;" rowspan="8">&nbsp;</td>
<td>INVOICE#: 00000000000004300065</td>
</tr>
<tr>
<td>Address:105,5EA,DAFZA DUBAI,UAE</td>
<td>Date: {{$orders[0]->order_date }}</td>
</tr>
<tr>
<td>UNITED ARAB EMIRATES</td>
<td>Order ID: 
@foreach ($orders as $item)
	{{$item->order_id}} /
@endforeach	
</td>
</tr>
<tr>
<td>Email: info@theluxuryunlimited.com</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Numbers of parcels: {{count($orders)}}</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Total actual weight 0.41 kg</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>The currency of sale: {{$orders->currency}}</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>Incoterms: DDP</td>
</tr>
<tr>
<th>SHIP TO CONSIGNEE</th>
<th>&nbsp;</th>
<th>SOLD TO PARTY</th>
</tr>
<tr>
<td>Client name : @if($shipping) {{ $shipping->firstname." ".$shipping->lastname }} @endif</td>
<td style="width: 30%;" rowspan="8">&nbsp;</td>
<td>Client name : @if($billing) {{ $billing->firstname." ".$billing->lastname }} @endif</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>Phone : @if($shipping) {{ $shipping->telephone }} @endif</td>
<td>Phone : @if($billing) {{ $billing->telephone }} @endif</td>
</tr>
<tr>
<td>Address : @if($shipping) {{ $shipping->street }} @endif</td>
<td>Address : @if($billing) {{ $billing->street }} @endif</td>
</tr>
<tr>
<td>City : @if($shipping) {{ $shipping->city }} @endif</td>
<td>City : @if($billing) {{ $billing->city }} @endif</td>
</tr>
<tr>
<td>Pincode : @if($shipping) {{ $shipping->postcode }} @endif</td>
<td>Pincode : @if($billing) {{ $billing->postcode }} @endif</td>
</tr>
<tr>
<td>Country : @if($shipping) {{ $shipping->country_id }} @endif</td>
<td>Country : @if($billing) {{ $billing->country_id }} @endif</td>
</tr>
</tbody>
</table>
<table id="customers">
<tbody>
<tr>
<th>DESCRIPTION</th>
<th>Country of origin</th>
<th>Units</th>
<th>UNIT V</th>
<th>TOTAL VALUE</th>
</tr>
{!! $orderItems !!}
<tr>
<td colspan="5">DO NOT CLEAR DIFFERENT COMMODITIES - UNDER A SINGAL HS CODE. USE HS COMMODITY CODES AS PROVIDED.</td>
</tr>
<tr>
<th colspan="2">DESCRIPTION</th>
<th colspan="2">TOTALS</th>
<th>&nbsp;</th>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td colspan="2">The total cost of foods (FOB) Shipping &amp; handling Insurance charges</td>
<td style="text-align: right;">
<p>INR 0.00</p>
<p>INR 0.00</p>
<p>INR {{$orderTotal}}</p>
</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td colspan="2">Total (CIF)</td>
<td style="text-align: right;">
<p>INR {{$orderTotal}}</p>
</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td colspan="2">Import Duty &amp; taxes due</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td colspan="2">Total Paid for the order</td>
<td style="text-align: right;">INR {{$orderTotal}}</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
<td colspan="2">&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="2">VALUE FOR CUSTOMERS:</td>
<td colspan="2">CIF</td>
<td style="text-align: right;">INR {{$orderTotal}}</td>
</tr>
</tbody>
</table>
@php	
@endphp
</body>
</html>