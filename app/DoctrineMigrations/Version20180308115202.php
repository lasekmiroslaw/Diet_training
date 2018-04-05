<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180308115202 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_cardio_training');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_cardio_training (id INT AUTO_INCREMENT NOT NULL, burnedCalories INT NOT NULL, time INT NOT NULL, date DATETIME NOT NULL, userId INT DEFAULT NULL, traingId INT DEFAULT NULL, INDEX IDX_60CC9C1464B64DCC (userId), INDEX IDX_60CC9C149C40445A (traingId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_cardio_training ADD CONSTRAINT FK_60CC9C1464B64DCC FOREIGN KEY (userId) REFERENCES entity_user (id)');
        $this->addSql('ALTER TABLE user_cardio_training ADD CONSTRAINT FK_60CC9C149C40445A FOREIGN KEY (traingId) REFERENCES cardio_training (id)');
    }
}
