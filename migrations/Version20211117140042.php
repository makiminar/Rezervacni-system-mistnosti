<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211117140042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create new entity request';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE request (id INT NOT NULL, date_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, state VARCHAR(20) DEFAULT \'PENDING\' NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE request_id_seq CASCADE');
        $this->addSql('DROP TABLE request');
    }
}
