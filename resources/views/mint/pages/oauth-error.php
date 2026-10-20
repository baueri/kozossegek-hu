<mint-extend path="layout/inner.php" :subtitle="OAuth hiba | " :page-title="Csatlakozási hiba" :inner-class="inner--auth">

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
            <div class="auth-card">
                <p class="mb-2"><strong><?php echo htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></strong></p>
                <p class="mb-0 text-muted"><?php echo htmlspecialchars((string) $description, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    </div>

</mint-extend>
