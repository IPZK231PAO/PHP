<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427192829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enrollment RENAME INDEX idx_dbdcd7e1cb944f1a TO IDX_9481D431CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE enrollment RENAME INDEX idx_dbdcd7e1591cc992 TO IDX_9481D431591CC992
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade RENAME INDEX idx_595aae34cb944f1a TO IDX_989B8130CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE grade RENAME INDEX idx_595aae34591cc992 TO IDX_989B8130591CC992
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE User
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Enrollment RENAME INDEX idx_9481d431cb944f1a TO IDX_DBDCD7E1CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Enrollment RENAME INDEX idx_9481d431591cc992 TO IDX_DBDCD7E1591CC992
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Grade RENAME INDEX idx_989b8130cb944f1a TO IDX_595AAE34CB944F1A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE Grade RENAME INDEX idx_989b8130591cc992 TO IDX_595AAE34591CC992
        SQL);
    }
}
