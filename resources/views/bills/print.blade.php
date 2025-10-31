<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>POS Bill</title>
    <style>
        body{
            background: rgb(232, 232, 232);
            font-size: 15px;
            font-family: "Helvetica";
        }
        .main{
            width: 80mm;
            background: #fff;
            overflow: hidden;
            margin: 0px auto;
            padding: 10px;
        }
        .logo{
            width: 100%;
            overflow: hidden;
            height: 130px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo img{
            width:80%;
        }
        .header p{
            margin: 2px 0px;
        }
        .content{
            overflow: hidden;
            width: 100%;
        }
        .content table{
            width: 100%;
            border-collapse: collapse;
        }

        .bg-dark{
            background: black;
            color:#ffff;
        }

        .text-left{
            text-align: left !important;
        }
        .text-right{
            text-align: right !important;
        }
        .text-center{
            text-align: center !important;
        }
        .area-title{

            font-size: 18px;
        }
        tr.bottom-border {
            border-bottom: 1px solid #ccc; /* Add a 1px solid border at the bottom of rows with the "my-class" class */
        }
        .uppercase{
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="main" id="main">
        <div style="text-align: center;">
           {{--  <img style="width:100%;margin:0 auto;height:100px;" src="{{ asset('assets/images/header.jpg') }}" alt=""> --}}
           <h2 style="font-family: Cambria;  margin:0;" >AL-QAMAR FILLING STATION</h2>
           <h3 style="font-family: Cambria;  margin:0;" >Dealer Attock Petroleum (Pvt) Ltd</h3>
           <h5 style="font-family: Cambria;  margin:0;" >Airport Road, Near Wapda Thermal House, Quetta <br> 081-2881313</h5>
         </div>
        <div class="header">
           {{--  <p class="text-center"><strong>081-2502481</strong></p>
            <p class="text-center"><strong>Fatima Jinnah Road Near Bugti Gali Zarghoon Plaza Quetta</strong></strong></p> --}}
            <div class="area-title">
                <p class="text-center bg-dark">Receipt</p>
            </div>
            <table>
                <tr>
                    <td >Receipt # </td>
                    <td> | {{ $sale->id }}</td>
                </tr>
                <tr>
                    <td >Date </td>
                    <td> | {{ date("d-m-Y", strtotime($sale->date)) }}</td>
                </tr>
                <tr>
                    <td >Vouchar #</td>
                    <td> | {{ $sale->vouchar }}</td>
                </tr>
                <tr>
                    <td > Vehicle: </td>
                    <td >| {{ $sale->vehicle->r_no }} </td>
                </tr>
                <tr>
                    <td > Department: </td>
                    <td >| {{ $sale->department->title }} </td>
                </tr>
            </table>
        </div>
        <div class="content">
            <table>
                <thead class="bg-dark">
                    <tr>
                        <th class="text-left" colspan="3">Description</th>
                    </tr>
                    <tr>
                        <th class="text-left">Qty</th>
                        <th class="text-left">Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($sale->details as $item)
                        <tr>
                            <td colspan="3" class="uppercase">{{ $item->product->name }} | {{ $item->product->unit }}</td>
                        </tr>
                        <tr class="bottom-border">
                            <td >{{ $item->qty }}</td>
                            <td>{{ round($item->price, 0) }}</td>
                            <td class="text-right">{{ round($item->amount,0)}}</td>
                        </tr>
                   @endforeach
                   <tr>
                    <td colspan="2" style="font-size: 9px !important">
                        
                    </td>
                    <td colspan="2" class="text-right" style="font-size: 18px"><strong>{{ number_format($sale->total,0) }}</strong></td>
                   </tr>
                </tbody>
            </table>
        </div>
        <div class="notes">
           
            <span>{{ $sale->note }}</span>
            <hr>
            <h5 class="text-center">Thank your for your business</h5>
        </div>
        <div class="footer">
            <hr>
            <h5 class="text-center">Developed by Diamond Software <br> diamondsoftwarequetta.com </h5>
        </div>
    </div>
</body>

</html>
<script src="{{ asset('src/plugins/src/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
setTimeout(function() {
    window.print();
    }, 1000);
        setTimeout(function() {
            window.location.href = "{{ route('sale.index')}}";
    }, 1000);

</script>
