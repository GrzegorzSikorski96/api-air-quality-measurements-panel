<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240218150044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changed Measurement column name from parameter_id to measurement_parameter_id';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE measurement RENAME COLUMN parameter_id TO measurement_parameter_id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE measurement RENAME COLUMN measurement_parameter_id TO parameter_id');
    }
}
