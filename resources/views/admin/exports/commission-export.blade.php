<table style="width: 100%; border-collapse: collapse;">
    <thead>
    <tr>
        <th style="width: 60%;">ID</th>
        <th style="width: 120%;">Full Name</th>
        <th style="width: 170%;">Mobile Number</th>
        <th style="width: 170%;">Total Earning</th>
        <th style="width: 60%;">TDS</th>
        <th style="width: 160%;">Admin Charges</th>
        <th style="width: 160%;">Other Charges</th>
        <th style="width: 180%;">Payable Amount</th>
        <th style="width: 180%;">Bank Name</th>
        <th style="width: 180%;">Branch</th>
        <th style="width: 180%;">Account Type</th>
        <th style="width: 240%;">Account Number</th>
        <th style="width: 200%;">IFSC Code</th>
        <th style="width: 180%;">Transaction ID</th>
        <th style="width: 180%;">Payment Date</th>
        <th style="width: 180%;">Payment Method</th>
        <th style="width: 180%;">Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($commissions as $commission)
        <tr>
            <td style="text-align: center;">{{ $commission->id }}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->name }}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->mobile }}</td>
            <td style="text-align: center;">{{ $commission->total_commission }}</td>
            <td style="text-align: center;">{{ $commission->tds }}</td>
            <td style="text-align: center;">{{ $commission->admin_charges }}</td>
            <td style="text-align: center;">{{ $commission->other_charges }}</td>
            <td style="text-align: center;">{{ $commission->total_earned }}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->bank_name}}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->bank_branch }}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->account_name}}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->account_number}}</td>
            <td style="text-align: center;">{{ $commission->customer->customerparent->account_ifsc}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
