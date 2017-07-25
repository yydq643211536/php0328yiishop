<form method="post">
    姓名：<input type="text" name="name" /><br>
    年龄：<input type="text" name="age" /><br>
    <input type="submit" value="提交">
    <input name="_csrf-backend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
</form>