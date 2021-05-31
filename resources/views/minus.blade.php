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
    <table class="fixed_header">
        <thead>
          <tr>
            <th>id</th>
            <th>isbn</th>
            <th>name</th>
            <th>branch</th>
            <th>qty</th>

          </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                <td>{{$product->id}}</td>
                <td>{{$product->isbn}}</td>
                <td>{{$product->name}}</td>
                <td>{{$product->branch_name}}</td>
                <td>{{$product->quantity}}</td>
              </tr>
            @endforeach
          
         
        </tbody>
      </table>
</body>
</html>