<h3 align="center">LAPORAN TRANSAKSI GRIYA SEHAT</h3>
<p align="center">{{ date('d M Y') }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <tr>
        <th>No</th>
        <th>Pasien</th>
        <th>Layanan</th>
        <th>Biaya</th>
        <th>Tanggal</th>
    </tr>

    @foreach($transactions as $t)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $t->queue->patient->name }}</td>
        <td>{{ $t->service_name }}</td>
        <td>Rp {{ number_format($t->amount) }}</td>
        <td>{{ $t->transaction_date }}</td>
    </tr>
    @endforeach
</table>

<p>Total Transaksi: 
<strong>Rp {{ number_format($transactions->sum('amount')) }}</strong>
</p>
