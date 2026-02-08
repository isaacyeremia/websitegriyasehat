<form method="POST" action="/ambil-antrian">
    @csrf
    <input type="text" name="name" placeholder="Nama">
    <input type="text" name="phone" placeholder="No HP">
    <button type="submit">Ambil Antrian</button>
</form>
