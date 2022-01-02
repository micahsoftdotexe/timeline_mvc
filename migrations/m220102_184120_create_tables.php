<?php

use yii\db\Migration;

/**
 * Class m220102_184120_create_tables
 */
class m220102_184120_create_tables extends Migration
{
    private function createTableUser()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'first_name' => $this->string(),
            'last_name'  => $this->string(),
        ]);

        $this->insert('user', [
            'username' => 'admin',
            'password' => Yii::$app->security->generatePasswordHash('admin'),
            'auth_key' => 'test100key',
            'first_name' => 'System',
            'last_name' => 'Administrator'
        ]);
        $this->insert('user', [
            'username' => 'demo',
            'password' => Yii::$app->security->generatePasswordHash('demo'),
            'auth_key' => '101-key',
            'first_name' => 'System',
            'last_name' => 'Demo'
        ]);
    }

    private function createTableClockEntries()
    {
        $this->createTable('clock_entries', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'clock_in_time' => $this->datetime()->notNull(),
            'clock_out_time' => $this->datetime(),
            'wage' => $this->decimal(10, 2),
        ]);
    }

    private function foreign_key_up()
    {
        $this->addForeignKey('fk_clock_entries_user', 'clock_entries', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeUp()
    {
        $this->createTableUser();
        $this->createTableClockEntries();
        $this->foreign_key_up();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_clock_entries_user', 'clock_entries');
        $this->dropTable('user');
        $this->dropTable('clock_entries');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220102_184120_create_tables cannot be reverted.\n";

        return false;
    }
    */
}
