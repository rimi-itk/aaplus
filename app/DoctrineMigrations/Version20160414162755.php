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
class Version20160414162755 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Bygning ADD projektleder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Bygning ADD CONSTRAINT FK_FD908E3750E86577 FOREIGN KEY (projektleder_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_FD908E3750E86577 ON Bygning (projektleder_id)');
        $this->addSql('ALTER TABLE Bygning_audit ADD projektleder_id INT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Bygning DROP FOREIGN KEY FK_FD908E3750E86577');
        $this->addSql('DROP INDEX IDX_FD908E3750E86577 ON Bygning');
        $this->addSql('ALTER TABLE Bygning DROP projektleder_id');
        $this->addSql('ALTER TABLE Bygning_audit DROP projektleder_id');
    }
}
