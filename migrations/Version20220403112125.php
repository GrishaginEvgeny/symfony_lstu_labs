<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403112125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD blog_token VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE text text LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE picture picture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE text text LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE pictures pictures LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE user DROP blog_token, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE token token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blog_name blog_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blog_caption blog_caption LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blog_category blog_category VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE blog_picture blog_picture VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE user_avatar user_avatar VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}