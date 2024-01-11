<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240107190821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Measurement Parameter table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE measurement_parameter (id UUID NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, formula VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B754F735E237E06 ON measurement_parameter (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B754F7377153098 ON measurement_parameter (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8B754F7367315881 ON measurement_parameter (formula)');
        $this->addSql('COMMENT ON COLUMN measurement_parameter.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE measurement_parameter');
    }
}
