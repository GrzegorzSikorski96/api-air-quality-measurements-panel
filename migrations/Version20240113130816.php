<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240113130816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Device table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE device (id UUID NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, external_id VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, provider VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_92FB68E5E237E06 ON device (name)');
        $this->addSql('COMMENT ON COLUMN device.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN device.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE device');
    }
}
