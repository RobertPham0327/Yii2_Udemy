<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%video_view}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%videos}}`
 * - `{{%user}}`
 */
class m240718_065938_create_video_view_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%video_view}}', [
            'id' => $this->primaryKey(),
            'video_id' => $this->string(16),
            'user_id' => $this->integer(11),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
        ]);

        // creates index for column `video_id`
        $this->createIndex(
            '{{%idx-video_view-video_id}}',
            '{{%video_view}}',
            'video_id'
        );

        // add foreign key for table `{{%videos}}`
        $this->addForeignKey(
            '{{%fk-video_view-video_id}}',
            '{{%video_view}}',
            'video_id',
            '{{%videos}}',
            'video_id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-video_view-user_id}}',
            '{{%video_view}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-video_view-user_id}}',
            '{{%video_view}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%videos}}`
        $this->dropForeignKey(
            '{{%fk-video_view-video_id}}',
            '{{%video_view}}'
        );

        // drops index for column `video_id`
        $this->dropIndex(
            '{{%idx-video_view-video_id}}',
            '{{%video_view}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-video_view-user_id}}',
            '{{%video_view}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-video_view-user_id}}',
            '{{%video_view}}'
        );

        $this->dropTable('{{%video_view}}');
    }
}
