<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170719_062002_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_detail', [
            'article_id' => $this->primaryKey(),
            'content'=>$this->text()->comment('文件详情')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
