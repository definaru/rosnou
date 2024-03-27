<?php

use yii\db\Migration;

/**
 * Handles the dropping for table `column_login_in_user`.
 */
class m170815_124532_drop_column_login_in_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%user}}', 'login');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {

    }
}
