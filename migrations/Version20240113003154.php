<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240113003154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Measurement table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE measurement (id UUID NOT NULL, parameter_id UUID NOT NULL, value DOUBLE PRECISION NOT NULL, recorded_at TIMESTAMP(0) WITHOUT TIME ZONE, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN measurement.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN measurement.parameter_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN measurement.recorded_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE measurement');
    }
}
