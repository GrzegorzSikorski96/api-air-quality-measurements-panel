<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240115120118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added deviceId into measurement table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement ADD device_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN measurement.device_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE measurement DROP device_id');
    }
}
