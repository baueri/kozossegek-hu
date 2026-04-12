<form id="contact-modal-ajax-form" class="contact-modal__form" method="post" action="#" autocomplete="on">
    <div class="row g-2">
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label small text-muted mb-1">Neved*</label>
                <input type="text" name="name" class="form-control login-prompt-input" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label small text-muted mb-1">Email címed*</label>
                <input type="email" name="email" class="form-control login-prompt-input" required>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label small text-muted mb-1">Üzenet</label>
        <textarea class="form-control login-prompt-input" name="message" rows="5" required><?php
            echo "Kedves {$group->group_leaders}!\n\nÉrdeklődni szeretnék, hogy lehet-e csatlakozni a {$group->name} közösségbe?";
        ?></textarea>
    </div>

    <mod-honeypot :id="group-contact" />

    <div class="mb-0">
        <label class="contact-modal__legal d-flex align-items-start gap-2 small text-muted">
            <input type="checkbox" class="mt-1" required>
            <span>Az <a href="@route('portal.page', 'adatkezelesi-tajekoztato')" target="_blank" rel="noopener noreferrer">adatvédelmi tájékoztatót</a> elolvastam és elfogadom</span>
        </label>
    </div>
</form>