@title('Intézmények importálása')
@extends('admin')
@alert('info')
    Válaszd ki az intézményeket tartalmazó <b>csv</b> fájlt. Ügyelj arra, hogy a táblázat első sora a fejléc legyen, illetve a csv mezőinek elválasztója vessző legyen, a mezők értékei pedig idézőjelben legyenek.
@endalert
<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="import_file">CSV fájl kiválasztása</label><br/>
        <input type="file" name="import_file">
    </div>
    @csrf()
    <button type="submit" class="btn btn-success">indítás</button>
</form>