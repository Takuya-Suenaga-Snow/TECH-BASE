<!DOCTYPE html>
<html lang = 'ja'>
<head>
    <meta charset='utf-8'>
    <title>Mission5</title>
</head>
<body>
    <?php
        $dsn = 'mysql:dbname=tb221084db;host=localhost';
        $username = 'tb-221084';
        $password = 'uC5PKrnAfA';
        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        try{
            $pdo = new PDO($dsn, $username, $password, $options);
            $sql = 'CREATE TABLE IF NOT EXISTS Mission5(
            id INT AUTO_INCREMENT PRIMARY KEY,
            name char(20),
            comment TEXT,
            time char(20),
            password char(20)
            )ENGINE = InnoDB default charset = utf8mb4';
            $stmt = $pdo -> query($sql);

            if(isset($_POST['submit'])){
                $edit_num = $_POST['edit_num'];
                $name = $_POST['name'];
                $comment = $_POST['comment'];
                $time = date('Y/m/d H:i:s');
                $pw = $_POST['password'];
                if($name == ''){
                    $error1 = '！名前を入力してください！';
                }else{
                    $error1 = '';
                }
                if($comment == ''){
                    $error2 = '！コメントを入力してください！';
                }else{
                    $error2 = '';
                }
                if($pw == ''){
                    $error3 = '！パスワードを入力してください！';
                }else{
                    $error3 = '';
                }
                if($name != '' && $comment != '' && $pw != ''){
                    if($edit_num == ''){    
                        $sql = $pdo -> prepare('INSERT INTO Mission5 (name, comment, time, password) VALUES (:name, :comment, :time, :password)');
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	                    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	                    $sql -> bindParam(':time', $time, PDO::PARAM_STR);
	                    $sql -> bindParam(':password', $pw, PDO::PARAM_STR);
                        $sql -> execute();
                    }else{
                        $sql = 'SELECT * FROM Mission5';
	                    $stmt = $pdo -> query($sql);
                        $results = $stmt -> fetchAll();
	                    foreach ($results as $row){
                            if($edit_num == $row['id']){
                                if($pw == $row['password']){
                                    $id = intval($edit_num);
                                    $sql = 'UPDATE Mission5 SET name = :name, comment = :comment, time = :time, password = :password  WHERE id = :id';
                                    $stmt = $pdo->prepare($sql);
	                                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	                                $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
	                                $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
	                                $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
	                                $stmt -> bindParam(':password', $pw, PDO::PARAM_STR);
                                    $stmt -> execute();
                                }else{
                                    $error3 = '！パスワードが違います！';
                                }
                            }
                        }
                    }
                }
                $edit_num = '';
                $name = '';
                $comment = '';
            }
            if(isset($_POST['delete'])){
                $del_num = $_POST['del_num'];
                $pw = $_POST['password'];
                if($pw == ''){
                    $error5 = '！パスワードを入力してください！';
                }else{
                    $error5 = '';
                }
                if($del_num == ''){
                    $error4 = '！番号を入力してください！';
                }else{
                    $error4 = '';
                }
                if($del_num != '' && $pw != ''){
                    $error4='！番号が不正です！';
                    $sql = 'SELECT * FROM Mission5';
	                $stmt = $pdo -> query($sql);
                    $results = $stmt -> fetchAll();
	                foreach ($results as $row){
                        if($del_num == $row['id']){
                            $error4 = '';
                            if($pw == $row['password']){
                                $id = intval($del_num);
                                $sql = 'DELETE FROM Mission5 WHERE id = :id';
	                            $stmt = $pdo -> prepare($sql);
                                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                                $stmt -> execute();
                            }else{
                                $error5 = '！パスワードが違います！';
                            }
                        }
                    }
                }
            }
            if(isset($_POST['edit'])){
                $edit_num = $_POST['edit_num'];
                if($edit_num == ''){
                    $error6 = '！番号を入力してください！';
                }else{
                    $error6 = '！番号が不正です！';
                    $sql = 'SELECT * FROM Mission5';
	                $stmt = $pdo -> query($sql);
                    $results = $stmt -> fetchAll();
	                foreach ($results as $row){
                        if($edit_num == $row['id']){
                            $error6 = '';
                            $name = $row['name'];
                            $comment = $row['comment'];
                        }
                    }
                    if($error6 != ''){
                        $edit_num = '';
                    }
                }
            }
        }catch(PDOException $e){
            echo $e -> getMessage();
            exit;
        }
        $pdo = null;
    ?>
    <form action='' method='post'>
        <input type='hidden' name='edit_num' value=<?php if(isset($edit_num)){echo $edit_num;}?>><br>
        <label>名前<br><input type='text' name='name' value="<?php if(isset($name)){echo $name;}?>"></label>
        <?php if(isset($error1)){echo $error1;}?><br>
        <label>コメント<br><input type='text' name='comment' value="<?php if(isset($comment)){echo $comment;}?>"></label>
        <?php if(isset($error2)){echo $error2;}?><br>
        <label>パスワード<br><input type='text' name='password'></label>
        <?php if(isset($error3)){echo $error3;}?><br>
        <input type='submit' name='submit' value='送信'>
    </form><br>
    <form action='' method='post'>
        <label>削除対象番号<br><input type='number' name='del_num'></label>
        <?php if(isset($error4)){echo $error4;}?><br>
        <label>パスワード<br><input type='text' name='password'></label>
        <?php if(isset($error5)){echo $error5;}?><br>
        <input type='submit' name='delete' value='削除'>
    </form><br>
    <form action='' method='post'>
        <label>編集対象番号<br><input type='number' name='edit_num'></label>
        <?php if(isset($error6)){echo $error6;}?><br>
        <input type='submit' name='edit' value='編集'>
    </form>
</body>
</html>