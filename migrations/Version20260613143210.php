<?php
declare(strict_types=1);
namespace DoctrineMigrations;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260613143210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add share_token to score';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE score ADD share_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_32993751D6594DD6 ON score (share_token)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_32993751D6594DD6 ON score');
        $this->addSql('ALTER TABLE score DROP share_token');
    }
}