<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240216213727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created DeviceMeasurementParameter table. Removed nullability on recorded_at in measurement table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE device_measurement_parameter (id UUID NOT NULL, device_id UUID NOT NULL, measurement_parameter_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9A6653794A4C7D46778C5CB ON device_measurement_parameter (device_id, measurement_parameter_id)');
        $this->addSql('COMMENT ON COLUMN device_measurement_parameter.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN device_measurement_parameter.device_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN device_measurement_parameter.measurement_parameter_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE measurement ALTER recorded_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE device_measurement_parameter');
        $this->addSql('ALTER TABLE measurement ALTER recorded_at DROP NOT NULL');
    }
}
