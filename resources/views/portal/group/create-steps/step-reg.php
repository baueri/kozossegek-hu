@extends('portal.group.create-steps.create-wrapper')
<div class="step-container">
    <div class="row">
        <div class="col-md-4 offset-4">
            <h3 class="text-center">Felhasználói adatok</h3>
            <form method="post" id="step-reg">
                <input type="hidden" name="next_step" value="group_data">
                <div class="form-group required">
                    <label>Neved:</label>
                    <input type="text" class="form-control form-control-sm" name="user_name" required value="{{ $user_name }}" data-describedby="validate_user_name">
                    <div id="validate_user_name" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Email címed:</label>
                    <input type="email" class="form-control form-control-sm" name="email" value="{{ $email }}" required data-describedby="validate_email">
                    <div id="validate_email" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Jelszó:</label>
                    <input type="password" name="password" class="form-control form-control-sm" required data-describedby="validate_password">
                    <div id="validate_password" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Jelszó még egyszer:</label>
                    <input type="password" name="password_again" class="form-control form-control-sm" required data-describedby="validate_password_again">
                    <div id="validate_password_again" class="validate_message"></div>
                </div>
                <button type="submit" class="btn btn-darkblue">Tovább</button>
            </form>
        </div>
    </div>
</div>
@footer()
    <script>
        $(() => {
            const form = $("#step-reg");

            function validateUserName()
            {
                const item = $("[name=user_name]", form);

                item.inputMessage("dismiss");
                if (item.val() !== "") {
                    item.inputOk();
                    return true;
                }

                item.inputError();
                return false;
            }

            async function validateEmailAddress()
            {
                const item = $("[name=email]", form);

                if (item.val() === "") {
                    item.inputError("show");
                    return false;
                } else if (!validate_email(item.val())) {
                    item.inputError("show", "Kérjük valós email címet adj meg.");
                    return false;
                } else {
                    var ok = false;
                    item.inputError("dismiss");
                    $.post("@route('api.check-email')", {email: item.val()}, await function (response) {
                        if (!response.ok) {
                            item.inputError("show", "Ez az email cím már foglalt!");
                            ok = false;
                        } else {
                            item.inputError("dismiss").inputOk();
                            ok = true;
                        }
                    });

                    return ok;
                }
            }

            function validatePassword()
            {
                const password = $("[name=password]");
                const password_again = $("[name=password_again]");

                if (password.val() !== "" && password_again.val() === "") {
                    password.inputError();
                    return false;
                } else if (password_again.val() !== "" && password.val() !== password_again.val()) {
                    password.inputError();
                    password_again.inputError("show", "A két jelszó nem egyezik");
                    return false;
                } else if (password_again.val() === "") {
                    password_again.inputError();
                    return false;
                }

                password.inputError("dismiss").inputOk();
                password_again.inputError("dismiss").inputOk();

                return true;
            }

            $("[name=user_name]").on("change, focusout", function () {
                validateUserName($(this));
            });

            $("[name=email]", form).on("change, focusout", function () {
                validateEmailAddress($(this));
            });

            $("[name=password], [name=password_again]", form).change(function () {
                validatePassword();
            });

            form.submit(async function(e) {
                if(!validateUserName() || !await validateEmailAddress() || !validatePassword()) {
                    e.preventDefault();
                }
            });
        })
    </script>
@endfooter
