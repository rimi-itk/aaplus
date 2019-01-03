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
class Version20160802113736 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tiltag ADD genopretningForImplementeringsomkostninger NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE Tiltag_audit ADD genopretningForImplementeringsomkostninger NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE Rapport ADD genopretningForImplementeringsomkostninger NUMERIC(10, 0) DEFAULT NULL');
        $this->addSql('ALTER TABLE Rapport_audit ADD genopretningForImplementeringsomkostninger NUMERIC(10, 0) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tiltag DROP genopretningForImplementeringsomkostninger');
        $this->addSql('ALTER TABLE Tiltag_audit DROP genopretningForImplementeringsomkostninger');
        $this->addSql('ALTER TABLE Rapport DROP genopretningForImplementeringsomkostninger');
        $this->addSql('ALTER TABLE Rapport_audit DROP genopretningForImplementeringsomkostninger');
    }
}
