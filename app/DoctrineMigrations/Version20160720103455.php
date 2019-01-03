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
class Version20160720103455 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tiltag ADD energiledelseAendringIBesparelseFaktor DOUBLE PRECISION DEFAULT NULL, ADD energiledelseNoter LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE Tiltag_audit ADD energiledelseAendringIBesparelseFaktor DOUBLE PRECISION DEFAULT NULL, ADD energiledelseNoter LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tiltag DROP energiledelseAendringIBesparelseFaktor, DROP energiledelseNoter');
        $this->addSql('ALTER TABLE Tiltag_audit DROP energiledelseAendringIBesparelseFaktor, DROP energiledelseNoter');
    }
}
