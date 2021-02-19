<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMiserendTemplomokTable extends AbstractMigration
{
    public function change(): void
    {
         $this->execute("CREATE TABLE miserend_templomok (
              id int(11) NOT NULL,
              nev varchar(150) NOT NULL DEFAULT '',
              ismertnev varchar(150) NOT NULL DEFAULT '',
              orszag int(2) NOT NULL DEFAULT '0',
              megye int(2) NOT NULL DEFAULT '0',
              varos varchar(100) NOT NULL DEFAULT '',
              cim varchar(250) NOT NULL DEFAULT '',
              plebania text NOT NULL,
              pleb_url varchar(100) NOT NULL DEFAULT '',
              egyhazmegye int(2) NOT NULL DEFAULT '0',
              espereskerulet int(3) NOT NULL DEFAULT '0',
              frissites date NOT NULL default '0000-00-00'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }
}
