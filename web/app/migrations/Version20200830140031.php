<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200830140031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_request (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, phone VARCHAR(20) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, message VARCHAR(500) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, date DATETIME DEFAULT NULL, licensed TINYINT(1) DEFAULT NULL, INDEX IDX_69AACBE27975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, context VARCHAR(64) NOT NULL, default_format VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery_has_media (id INT AUTO_INCREMENT NOT NULL, gallery_id INT DEFAULT NULL, media_id INT DEFAULT NULL, position INT NOT NULL, enabled TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CD2356644E7AF8F (gallery_id), INDEX IDX_CD235664EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE highlight (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, description LONGTEXT NOT NULL, location VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, position INT NOT NULL, INDEX IDX_C998D8347975B7E7 (model_id), INDEX IDX_C998D8343DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lineup (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, slug VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CD7E0ECA5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, provider_name VARCHAR(255) NOT NULL, provider_status INT NOT NULL, provider_reference VARCHAR(255) NOT NULL, provider_metadata JSON DEFAULT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, length NUMERIC(10, 0) DEFAULT NULL, content_type VARCHAR(255) DEFAULT NULL, content_size INT DEFAULT NULL, copyright VARCHAR(255) DEFAULT NULL, author_name VARCHAR(255) DEFAULT NULL, context VARCHAR(64) DEFAULT NULL, cdn_is_flushable TINYINT(1) DEFAULT NULL, cdn_flush_identifier VARCHAR(64) DEFAULT NULL, cdn_flush_at DATETIME DEFAULT NULL, cdn_status INT DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, lineup_id INT DEFAULT NULL, logo_id INT DEFAULT NULL, image_id INT DEFAULT NULL, header_id INT DEFAULT NULL, gallery_id INT DEFAULT NULL, sound_id INT DEFAULT NULL, brochure_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slogan VARCHAR(255) DEFAULT NULL, price NUMERIC(10, 2) NOT NULL, stock_status VARCHAR(255) NOT NULL, special TINYINT(1) NOT NULL, ride TINYINT(1) NOT NULL, specs JSON NOT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_D79572D9D347A7DE (lineup_id), INDEX IDX_D79572D9F98F144A (logo_id), INDEX IDX_D79572D93DA5256D (image_id), INDEX IDX_D79572D92EF91FD8 (header_id), INDEX IDX_D79572D94E7AF8F (gallery_id), INDEX IDX_D79572D96AAA5C3E (sound_id), INDEX IDX_D79572D9B96114D1 (brochure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paint (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, paint_color_id INT DEFAULT NULL, gallery_id INT DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) NOT NULL, position INT NOT NULL, INDEX IDX_577A84177975B7E7 (model_id), INDEX IDX_577A841712594967 (paint_color_id), INDEX IDX_577A84174E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paint_color (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_DBAAF8C95E237E06 (name), INDEX IDX_DBAAF8C93DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_request ADD CONSTRAINT FK_69AACBE27975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE gallery_has_media ADD CONSTRAINT FK_CD2356644E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gallery_has_media ADD CONSTRAINT FK_CD235664EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE highlight ADD CONSTRAINT FK_C998D8347975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE highlight ADD CONSTRAINT FK_C998D8343DA5256D FOREIGN KEY (image_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9D347A7DE FOREIGN KEY (lineup_id) REFERENCES lineup (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9F98F144A FOREIGN KEY (logo_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D93DA5256D FOREIGN KEY (image_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D92EF91FD8 FOREIGN KEY (header_id) REFERENCES gallery (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D94E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D96AAA5C3E FOREIGN KEY (sound_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9B96114D1 FOREIGN KEY (brochure_id) REFERENCES media (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paint ADD CONSTRAINT FK_577A84177975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE paint ADD CONSTRAINT FK_577A841712594967 FOREIGN KEY (paint_color_id) REFERENCES paint_color (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paint ADD CONSTRAINT FK_577A84174E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE paint_color ADD CONSTRAINT FK_DBAAF8C93DA5256D FOREIGN KEY (image_id) REFERENCES media (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery_has_media DROP FOREIGN KEY FK_CD2356644E7AF8F');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D92EF91FD8');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D94E7AF8F');
        $this->addSql('ALTER TABLE paint DROP FOREIGN KEY FK_577A84174E7AF8F');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9D347A7DE');
        $this->addSql('ALTER TABLE gallery_has_media DROP FOREIGN KEY FK_CD235664EA9FDD75');
        $this->addSql('ALTER TABLE highlight DROP FOREIGN KEY FK_C998D8343DA5256D');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9F98F144A');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D93DA5256D');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D96AAA5C3E');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9B96114D1');
        $this->addSql('ALTER TABLE paint_color DROP FOREIGN KEY FK_DBAAF8C93DA5256D');
        $this->addSql('ALTER TABLE client_request DROP FOREIGN KEY FK_69AACBE27975B7E7');
        $this->addSql('ALTER TABLE highlight DROP FOREIGN KEY FK_C998D8347975B7E7');
        $this->addSql('ALTER TABLE paint DROP FOREIGN KEY FK_577A84177975B7E7');
        $this->addSql('ALTER TABLE paint DROP FOREIGN KEY FK_577A841712594967');
        $this->addSql('DROP TABLE client_request');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE gallery_has_media');
        $this->addSql('DROP TABLE highlight');
        $this->addSql('DROP TABLE lineup');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE paint');
        $this->addSql('DROP TABLE paint_color');
        $this->addSql('DROP TABLE users');
    }
}
