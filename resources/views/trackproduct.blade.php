<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>check the stock</title>
    <style>
        .fixed_header{
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

.fixed_header tbody{
  display:block;
  width: 100%;
  overflow: auto;
  height: auto;
}

.fixed_header thead tr {
   display: block;
}

.fixed_header thead {
  background: black;
  color:#fff;
}

.fixed_header th, .fixed_header td {
  padding: 5px;
  text-align: left;
  width: 200px;
}
    </style>
</head>
<body>
    <h1>transactions</h1>
    <table class="fixed_header">
        <thead>
          <tr>
            <th>id</th>
            <th>qty</th>
            <th>from branch</th>
            <th>to branch</th>
            <th>created at</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                <td><a href="http://localhost:8080/transaction/{{$transaction->id}}" target="_blank">{{$transaction->id}}</a></td>
                <td>{{$transaction->qty}}</td>
                <td>{{$transaction->from_branch}}</td>
                <td>{{$transaction->branch_id}}</td>
                <td>{{$transaction->created_at}}</td>
              </tr>
            @endforeach
          
         
        </tbody>
      </table>
      
      <br>
      
         <h1>outcoming orders</h1>
    <table class="fixed_header">
        <thead>
          <tr>
            <th>id</th>
            <th>qty</th>
            <th>branch</th>
            <th>created at</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($outcoming as $order)
                <tr>
                <td><a href="http://localhost:8080/outcoming_order/{{$order->id}}" target="_blank">{{$order->id}}</a></td>
                <td>{{$order->qty}}</td>
                <td>{{$order->branch}}</td>
                  <td>{{$order->created_at}}</td>
              </tr>
            @endforeach
          
         
        </tbody>
      </table>
      
       <br>
      
         <h1>orders</h1>
    <table class="fixed_header">
        <thead>
          <tr>
            <th>id</th>
            <th>qty</th>
            <th>branch</th>
            <th>created at</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                <td><a href="http://localhost:8080/order/{{$order->id}}" target="_blank">{{$order->id}}</a></td>
                <td>{{$order->qty}}</td>
                <td>{{$order->branch}}</td>
                  <td>{{$order->created_at}}</td>
              </tr>
            @endforeach
          
         
        </tbody>
      </table>
     
</body>
</html>