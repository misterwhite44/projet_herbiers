<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231222111403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tables lieu, releve, and messenger_messages';
    }

    public function up(Schema $schema): void
    {
        // Create table lieu
        $this->createTableLieu($schema);

        // Create table releve
        $this->createTableReleve($schema);

        // Create table messenger_messages
        $this->createTableMessengerMessages($schema);

        // Add foreign key in releve table
        $this->addForeignKeyInReleve($schema);
    }

    public function down(Schema $schema): void
    {
        // Drop foreign key in releve table
        $this->dropForeignKeyInReleve($schema);

        // Drop tables in reverse order
        $schema->dropTable('messenger_messages');
        $schema->dropTable('releve');
        $schema->dropTable('lieu');
    }

    private function createTableLieu(Schema $schema): void
    {
        $table = $schema->createTable('lieu');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('nom', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    private function createTableReleve(Schema $schema): void
    {
        $table = $schema->createTable('releve');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('lieu_id', 'integer');
        $table->addColumn('date', 'datetime');
        $table->addColumn('releve_brut', 'string', ['length' => 255]);
        $table->addIndex(['lieu_id']);
        $table->setPrimaryKey(['id']);
    }

  

    private function addForeignKeyInReleve(Schema $schema): void
    {
        $table = $schema->getTable('releve');
        $table->addForeignKeyConstraint('lieu', ['lieu_id'], ['id']);
    }

    private function dropForeignKeyInReleve(Schema $schema): void
    {
        $table = $schema->getTable('releve');
        $table->removeForeignKey('FK_DDABFF836AB213CC');
    }
}
