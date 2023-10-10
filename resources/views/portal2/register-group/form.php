<com:template extends="portal2.main">
    <section id="register-group-title" class="section section-with-bg is-bold">
        <div class="container pt-4 pb-4 is-max-desktop">
            <h1 class="title has-text-white">Közösség regisztráció</h1>
        </div>
    </section>
    <section id="register-notification" class="mt-4 mb-4">
        <div class="container is-max-desktop">
            <com:Notification type="warning">
                <com:Icon class="exclamation-triangle mr-2"/>
                Fontos számunkra, hogy az oldalon valóban keresztény értékeket közvetítő közösségeket hirdessünk.<br/> Mielőtt kitöltenéd a regisztrációs űrlapot, kérjük, hogy mindenképp olvasd el az <a href="@route('portal.page', ['slug' => 'iranyelveink'])">irányelveinket</a>.
            </com:Notification>
        </div>
    </section>
    <section>
        <form id="register-group-form">
            <div class="container is-max-desktop">
                <com:Fieldset legend="Fiók adatok">
                    <com:TextInput type="text" label="Név" required="1" name="user_name" size="is-narrow"/>
                    <com:TextInput type="email" label="Email cím" required="1" name="email" size="is-narrow"/>
                    <com:TextInput type="tel" label="Telefonszám" name="phone_number" info="Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is" size="is-narrow"/>
                    <com:Password label="Jelszó" required="1" name="password" size="is-narrow"/>
                    <com:Password label="Jelszó mégegyszer" required="1" name="password_again" size="is-narrow"/>
                </com:Fieldset>

                <com:fieldset legend="Közösséged adatai">
                    <div class="columns">
                        <div class="column">
                            <com:TextInput label="Közösség neve" required="1" name="user_name"/>
                        </div>
                        <div class="column">
                            <com:TextInput label="Közösségvezető(k)" required="1" name="email"/>
                        </div>
                    </div>
                    <com:TextInput label="Intézmény / plébánia" name="phone_number" size=""/>
                    <div class="columns">
                        <div class="column">
                            <com:AgeGroupSelector label="Korosztály" name="age_group" required="1" multiple="1"/>
                        </div>
                        <div class="column">
                            <com:OccasionFrequencySelector label="Korosztály" name="age_group" required="1" multiple="1"/>
                        </div>
                    </div>
                </com:fieldset>
            </div>
        </form>
    </section>
</com:template>