<h3>Rekam Medis Pasien</h3>

<p>
Nama Pasien: <strong>{{ $queue->patient->name }}</strong><br>
No Antrian: <strong>{{ $queue->queue_number }}</strong>
</p>

<form method="POST">
@csrf
<textarea name="complaint" placeholder="Keluhan" required></textarea><br>
<textarea name="diagnosis" placeholder="Diagnosa" required></textarea><br>
<textarea name="treatment" placeholder="Tindakan" required></textarea><br>
<textarea name="medicine" placeholder="Obat"></textarea><br>
<textarea name="doctor_note" placeholder="Catatan Dokter"></textarea><br>

<button type="submit">Simpan Rekam Medis</button>
</form>
