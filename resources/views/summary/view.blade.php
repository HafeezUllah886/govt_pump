@extends('layout.popups')
@section('content')
        <div class="row justify-content-center">
            <div class="col-xxl-9">
                <div class="card" id="demo">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end d-print-none p-2 mt-4">
                                {{-- <a href="{{url('purchases/pdf/')}}/{{$purchase->id}}" class="btn btn-info ml-4"><i class="ri-file-line mr-4"></i> Generate PDF</a>
                                <a href="https://web.whatsapp.com/" target="_blank" class="btn btn-success ml-4"><i class="ri-whatsapp-line mr-4"></i> Whatsapp</a> --}}
                                <a href="javascript:window.print()" class="btn btn-success ml-4"><i class="ri-printer-line mr-4"></i> Print</a>
                            </div>
                            <div class="card-header border-bottom-dashed p-4">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h1>{{projectNameAuth()}}</h1>
                                    </div>
                                    
                                </div>
                            </div>
                            <!--end card-header-->
                        </div><!--end col-->
                        <div class="col-lg-12 p-4 pt-1 pb-1">
                           <div class="card-body">
                            <h3 class="text-center text-decoration-underline text-uppercase">SUMMARY FOR THE MONTH OF {{$month_name}}</h3>
                             <table class="table table-bordered border-2 border-dark">
                                <tbody>
                                    <tr class="no-padding p-1">
                                        <th class="no-padding p-1" width="20%" >Department</th>
                                        <td colspan="7" class="no-padding p-1">{{ $department->title }}</td>
                                    </tr>
                                </tbody>
                            </table>
                             <table class="table table-bordered border-2 border-dark">
                                <thead>
                                    <tr class="no-padding p-1 table-active" style="background-color: #b1b1b1;">
                                        <th class="no-padding p-1 text-center">Serial</th>
                                        <th class="no-padding p-1">Bill No.</th>
                                        <th class="no-padding p-1">Vehicle</th>
                                        <th class="no-padding p-1 text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bills as $key => $bill)
                                    <tr class="no-padding p-1">
                                        <td class="no-padding p-1 text-center">{{ $key+1 }}</td>
                                        <td class="no-padding p-1">{{ $bill->bill_no }}</td>
                                        <td class="no-padding p-1">{{ $bill->vehicle->r_no }} ({{ $bill->vehicle->type }})</td>
                                        <td class="no-padding p-1 text-end">{{ number_format($bill->total, 2) }}</td>
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="no-padding p-1 table-active" style="background-color: #b1b1b1;">
                                        <th colspan="3" class="text-end p-1">Total:</th>
                                        <th class="text-end p-1">{{ number_format($bills->sum('total'), 2) }}</th>
                                    </tr>
                                    <tr class="no-padding p-1 table-active" >
                                        <th colspan="4" class="text-center p-1">Rupees {{ numberToWords($bills->sum('total')) }} Only</th>
                                    </tr>
                                </tfoot>
                            </table>
                           </div>
                        </div><!--end col-->
                         <div class="col-lg-12 p-4 pt-1 pb-1">
                           <div class="card-body">
                            <h6 class="text-end">_______________ <br>Authorized Signature</h6>
                           </div>
                         </div>
                         <div class="col-lg-12 p-4 pt-1 pb-1">
                           <div class="card-body">
                            <h6 class="text-center">We request your cooperation in making timely payments and look forward to your continued quality service.</h6>
                           </div>
                         </div>
                    </div><!--end row-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->

@endsection

