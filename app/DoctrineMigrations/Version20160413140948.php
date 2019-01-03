<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160413140948 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Baseline ADD elBaselineFastsatForEjendomKorrektion DOUBLE PRECISION DEFAULT NULL, ADD varmeGAFForbrugKorrektion DOUBLE PRECISION DEFAULT NULL, ADD varmeGUFForbrugKorrektion DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE Baseline_audit ADD elBaselineFastsatForEjendomKorrektion DOUBLE PRECISION DEFAULT NULL, ADD varmeGAFForbrugKorrektion DOUBLE PRECISION DEFAULT NULL, ADD varmeGUFForbrugKorrektion DOUBLE PRECISION DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Baseline DROP elBaselineFastsatForEjendomKorrektion, DROP varmeGAFForbrugKorrektion, DROP varmeGUFForbrugKorrektion');
        $this->addSql('ALTER TABLE Baseline_audit DROP elBaselineFastsatForEjendomKorrektion, DROP varmeGAFForbrugKorrektion, DROP varmeGUFForbrugKorrektion');
    }
}
