<h2>Laporan Transaksi Griya Sehat</h2>

<form method="GET">
    <input type="date" name="start_date">
    <input type="date" name="end_date">
    <button type="submit">Filter</button>
    <a href="/admin/laporan-transaksi/pdf?start_date={{ request('start_date') }}&end_date={{ request('end_date') }}">
        Export PDF
    </a>
</form>

<table border="1" cellpadding="5">
    <tr>
        <th>No</th>
        <th>Nama Pasien</th>
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
