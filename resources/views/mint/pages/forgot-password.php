<mint-extend path="layout/inner.php" :subtitle="Új jelszó igénylése | " :page-title="Új jelszó igénylése">

    <?php echo view('admin.partials.message'); ?>

    <p class="mb-4">
        Add meg a fiókodhoz tartozó email címedet, amire küldünk egy levelet a további lépésekkel kapcsolatban!
    </p>
    <form method="post" class="row" action="@route('portal.reset_password')">
        @csrf
        <div class="col-md-4">
            <div class="mb-3 required">
                <label class="form-label" for="forgot-email">Email címed</label>
                <input type="email" class="form-control" id="forgot-email" name="email" required/>
            </div>

            <div class="mb-3">
                <input type="submit" class="btn btn-orange px-4 rounded-pill" value="Új jelszó igénylése">
            </div>
        </div>
    </form>

</mint-extend>
