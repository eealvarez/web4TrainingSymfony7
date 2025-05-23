<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521220235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, mama_id INT DEFAULT NULL, papa_id INT DEFAULT NULL, encargado_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, apellido VARCHAR(255) NOT NULL, fecha_nacimiento DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', dni VARCHAR(20) NOT NULL, direccion VARCHAR(255) DEFAULT NULL, telefono VARCHAR(20) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, discr VARCHAR(255) NOT NULL, numero_expediente VARCHAR(50) DEFAULT NULL, fecha_registro DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', diagnostico_principal LONGTEXT DEFAULT NULL, observaciones LONGTEXT DEFAULT NULL, ocupacion VARCHAR(255) DEFAULT NULL, estado_civil VARCHAR(50) DEFAULT NULL, empresa_trabajo VARCHAR(255) DEFAULT NULL, relacion_con_paciente VARCHAR(255) DEFAULT NULL, es_principal TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_51E5B69B7F8F253B (dni), UNIQUE INDEX UNIQ_51E5B69BE7927C74 (email), UNIQUE INDEX UNIQ_51E5B69BB56139B0 (numero_expediente), INDEX IDX_51E5B69BCF9D111C (mama_id), INDEX IDX_51E5B69B51E4BF21 (papa_id), INDEX IDX_51E5B69B4D75585E (encargado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BCF9D111C FOREIGN KEY (mama_id) REFERENCES persona (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B51E4BF21 FOREIGN KEY (papa_id) REFERENCES persona (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona ADD CONSTRAINT FK_51E5B69B4D75585E FOREIGN KEY (encargado_id) REFERENCES persona (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BCF9D111C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B51E4BF21
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69B4D75585E
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE persona
        SQL);
    }
}
