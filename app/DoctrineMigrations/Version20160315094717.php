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
class Version20160315094717 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('UPDATE Tiltag SET primaerEnterprise = \'\' WHERE primaerEnterprise IS NULL');
        $this->addSql('UPDATE Tiltag_audit SET primaerEnterprise = \'\' WHERE primaerEnterprise IS NULL');

        $this->addSql('ALTER TABLE Tiltag CHANGE primaerEnterprise primaerEnterprise ENUM(\'\', \'el\', \'t/i\', \'ve\', \'vvs\', \'hh\', \'a\', \'ia\', \'t\') NOT NULL COMMENT \'(DC2Type:PrimaerEnterpriseType)\'');
        $this->addSql('ALTER TABLE Tiltag_audit CHANGE primaerEnterprise primaerEnterprise ENUM(\'\', \'el\', \'t/i\', \'ve\', \'vvs\', \'hh\', \'a\', \'ia\', \'t\') DEFAULT NULL COMMENT \'(DC2Type:PrimaerEnterpriseType)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Tiltag CHANGE primaerEnterprise primaerEnterprise VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE Tiltag_audit CHANGE primaerEnterprise primaerEnterprise VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
