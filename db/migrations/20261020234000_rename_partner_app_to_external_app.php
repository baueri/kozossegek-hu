<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class RenamePartnerAppToExternalApp extends AppMigration
{
    public function up(): void
    {
        $this->execute(
            "UPDATE oauth_clients SET name = 'Külső alkalmazás' WHERE client_id = 'kozossegek-partner-sync' AND name = 'Partner alkalmazás'"
        );
        $this->execute(
            "UPDATE api_tokens SET name = 'Külső alkalmazás' WHERE grant_type = 'oauth' AND name = 'Partner alkalmazás'"
        );
    }

    public function down(): void
    {
        $this->execute(
            "UPDATE oauth_clients SET name = 'Partner alkalmazás' WHERE client_id = 'kozossegek-partner-sync' AND name = 'Külső alkalmazás'"
        );
        $this->execute(
            "UPDATE api_tokens SET name = 'Partner alkalmazás' WHERE grant_type = 'oauth' AND name = 'Külső alkalmazás'"
        );
    }
}
