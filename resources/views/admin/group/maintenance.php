@title('Karbantartás')
@extends('admin')
<div>
    <h4>Fiókok létrehozása felhasználóval nem rendelkező közösségek részére</h4>
    <p>
        A gomb megnyomásakor a közösségeknek, amikhez nincs karbantartó felhasználói fiók rendelve:
    <ul>
        <li>automatikusan létrehozunk egy új fiókot</li>
        <li>kiküldünk nekik egy emailt</li>
        <li>Az új fiókot a hozzá tartozó közösséghez csatoljuk</li>
    </ul>
    </p>
    <p><b>Felhasználóval nem rendelkező közösségek száma: {{ $groups->count() }}</b></p>
    @if(!is_prod())
        @alert('danger')
            <b>Figyelem!</b> Ez a demó oldal! Csak abban az esetben indítsd el a fiókok létrehozását, ha <b>valóban létre akarod hozni a fiókokat és ki akarod küldeni a regisztrációs email-eket</b>!<br><br><label for="confirm_demo"><input type="checkbox" name="confirm_demo" id="confirm_demo"/> <b>megértettem</b></label>
        @endalert
    @endif
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Email sablon:</label>
                    <select name="email_template" class="form-control">
                        <option value="register">Alapértelmezett regisztrációs sablon</option>
                        <option value="register_by_group">Honlap indításakor létrehozott csoportoknak</option>
                    </select>
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-primary {{ !is_prod() ? 'disabled' : '' }} " id="create_users" value="Fiókok létrehozása" @if(!is_prod())disabled@endif>
    </form>
</div>
<script>
    $(() => {
        $("#confirm_demo").change(function (){
            if ($(this).is(":checked")) {
                $("#create_users").removeAttr("disabled").removeClass("disabled");
            } else {
                $("#create_users").attr("disabled", true).addClass("disabled");
            }
        });
    });
</script>