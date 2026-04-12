<mint-extend path="layout/inner.php" :subtitle="{$pageTitle . ' | '}" :page-title="{$pageTitle}" :inner-class="inner--bare">

    <div class="portal-error text-center py-4 py-md-5">
        <div class="portal-error__box mx-auto">
            <?php if (!empty($code)): ?>
                <div class="portal-error__code-wrap">
                    <p class="portal-error__code"><?php echo htmlspecialchars((string) $code, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($message) && $message !== $pageTitle): ?>
                <h2 class="portal-error__heading"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></h2>
            <?php endif; ?>

            <?php if (!empty($message2)): ?>
                <div class="portal-error__text"><?php echo $message2; ?></div>
            <?php endif; ?>

            <p class="portal-error__back mb-0"><a href="@route('home')">Vissza a főoldalra</a></p>
        </div>
    </div>

</mint-extend>
