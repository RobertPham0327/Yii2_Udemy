<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%project}}`
 * - `{{%file}}`
 */
class m240701_071706_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull(),
            'file_id' => $this->integer()->notNull(),
        ]);
        // creates index for column `project_id`
        $this->createIndex(
            '{{%idx-image-project_id}}',
            '{{%image}}',
            'project_id'
        );

        // add foreign key for table `{{%project}}`
        $this->addForeignKey(
            '{{%fk-image-project_id}}',
            '{{%image}}',
            'project_id',
            '{{%project}}',
            'id',
            'CASCADE'
        );

        // creates index for column `file_id`
        $this->createIndex(
            '{{%idx-image-file_id}}',
            '{{%image}}',
            'file_id'
        );

        // add foreign key for table `{{%file}}`
        $this->addForeignKey(
            '{{%fk-image-file_id}}',
            '{{%image}}',
            'file_id',
            '{{%file}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%project}}`
        $this->dropForeignKey(
            '{{%fk-image-project_id}}',
            '{{%image}}'
        );

        // drops index for column `project_id`
        $this->dropIndex(
            '{{%idx-image-project_id}}',
            '{{%image}}'
        );

        // drops foreign key for table `{{%file}}`
        $this->dropForeignKey(
            '{{%fk-image-file_id}}',
            '{{%image}}'
        );

        // drops index for column `file_id`
        $this->dropIndex(
            '{{%idx-image-file_id}}',
            '{{%image}}'
        );

        $this->dropTable('{{%image}}');
    }
}
