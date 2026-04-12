<mint-extend path="layout/inner.php" :subtitle="Új jelszó megadása | " :page-title="Új jelszó megadása">

    <?php echo view('admin.partials.message'); ?>

    <form method="post" class="row" action="">
        @csrf
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label" for="new_password">Új jelszó</label>
                <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password">
            </div>

            <div class="mb-3">
                <label class="form-label" for="new_password_again">Új jelszó még egyszer</label>
                <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-orange px-4 rounded-pill">Új jelszó mentése</button>
            </div>
        </div>
    </form>

</mint-extend>
