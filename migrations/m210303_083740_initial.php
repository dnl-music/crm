<?php

use yii\db\Migration;

/**
 * Class m210303_083740_initial
 */
class m210303_083740_initial extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('item', [
            'id' => $this->primaryKey(),
            'article' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'remainder' => $this->integer()->notNull(),
            'unit' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210303_083740_initial cannot be reverted.\n";

        return false;
    }
    */
}
