<?php

use yii\db\Migration;

/**
 * Class m240702_073750_alter_date_columns_table
 */
class m240702_073750_alter_date_columns_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('project', 'start_date', $this->date());
        $this->alterColumn('project', 'end_date', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('project', 'start_date', $this->integer());
        $this->alterColumn('project', 'end_date', $this->integer());
    }
}
