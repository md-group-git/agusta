<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130062535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tech_section (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_9C689EAA727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tech_spec (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, tech_section_id INT DEFAULT NULL, text LONGTEXT NOT NULL, position INT NOT NULL, INDEX IDX_D6BD63DF7975B7E7 (model_id), INDEX IDX_D6BD63DF1E0553E4 (tech_section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tech_section ADD CONSTRAINT FK_9C689EAA727ACA70 FOREIGN KEY (parent_id) REFERENCES tech_section (id)');
        $this->addSql('ALTER TABLE tech_spec ADD CONSTRAINT FK_D6BD63DF7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE tech_spec ADD CONSTRAINT FK_D6BD63DF1E0553E4 FOREIGN KEY (tech_section_id) REFERENCES tech_section (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tech_section DROP FOREIGN KEY FK_9C689EAA727ACA70');
        $this->addSql('ALTER TABLE tech_spec DROP FOREIGN KEY FK_D6BD63DF1E0553E4');
        $this->addSql('DROP TABLE tech_section');
        $this->addSql('DROP TABLE tech_spec');
    }
}
