<h3>Riwayat Rekam Medis</h3>

<table border="1" cellpadding="5">
<tr>
    <th>Tanggal</th>
    <th>Keluhan</th>
    <th>Diagnosa</th>
    <th>Tindakan</th>
    <th>Obat</th>
</tr>

@foreach($records as $r)
<tr>
    <td>{{ $r->checkup_date }}</td>
    <td>{{ $r->complaint }}</td>
    <td>{{ $r->diagnosis }}</td>
    <td>{{ $r->treatment }}</td>
    <td>{{ $r->medicine }}</td>
</tr>
@endforeach
</table>
