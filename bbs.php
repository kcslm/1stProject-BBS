<html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>bbs</title>
    </head>
  
    <body>
        <?php
            //データベースへの接続
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $dbpassword = 'パスワード';
            $pdo = new PDO($dsn, $user, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
                
            //table名'コメント一覧1'の作成id.name.comment.datetime.password
            $sql = "CREATE TABLE IF NOT EXISTS コメント一覧1"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "datetime DATETIME,"
            . "password char(32)"
            .");";
            $stmt = $pdo->query($sql);
        ?>
        <form action="mission_5-1.php" method="post">
            <label>名前<br></label>
            <input type="text" name="name" value="<?php if(isset($_POST['hensyu'])){
                                                            $sql = 'SELECT * FROM コメント一覧1';
                                                            $stmt = $pdo->query($sql);
                                                            $results = $stmt->fetchAll();
                                                            foreach ($results as $row){
                                                                if($row['id'] == $_POST['hensyu']){//idで選んで
                                                                    if($row['password'] == $_POST['password3']){//passwordが一致する時
                                                                        echo $row['name'];
                                                                    }
                                                                }
                                                            }
                                                        }?>" required><br>
            <label>コメント<br></label>
            <input type="text" name="txt" value="<?php if(isset($_POST['hensyu'])){
                                                            $sql = 'SELECT * FROM コメント一覧1';
                                                            $stmt = $pdo->query($sql);
                                                            $results = $stmt->fetchAll();
                                                            foreach ($results as $row){
                                                                if($row['id'] == $_POST['hensyu']){//idで選んで
                                                                    if($row['password'] == $_POST['password3']){//passwordが一致する時
                                                                        echo $row['comment'];
                                                                    }
                                                                }
                                                            }
                                                        }?>" required><br>
            <input type="hidden" name="number" value="<?php if(isset($_POST['hensyu'])){
                                                            $sql = 'SELECT * FROM コメント一覧1';
                                                            $stmt = $pdo->query($sql);
                                                            $results = $stmt->fetchAll();
                                                            foreach ($results as $row){
                                                                if($row['id'] == $_POST['hensyu']){//idで選んで
                                                                    if($row['password'] == $_POST['password3']){//passwordが一致する時
                                                                        echo $row['id'];
                                                                    }
                                                                }
                                                            }
                                                        }?>">
            <label>パスワード<br></label>
            <input type="password" name="password1" required><br><br>
            <button>送信する</button><br><br>
        </form>
        <form action="mission_5-1.php" method="post" >
            <p>
            <p><input type="text" name="delete" placeholder="削除対象番号" required></p>
            <input type="password" name="password2" placeholder="パスワード" required><br><br>
            <input type="submit" name="send_delete" value="削除"><br><br>
            </p>
        </form>
        <form action="mission_5-1.php" method="post">
            <p>
            <p><input type="text" name="hensyu" placeholder="編集対象番号" required></p>
            <input type="password" name="password3" placeholder="パスワード" required><br><br>
            <input type="submit" name="send_hensyu" value="編集"><br>
            </p>
        </form>

        <?php    
            //新規投稿機能
            if (isset($_POST['name']) && isset($_POST['txt']) && isset($_POST['password1']) ){
                if(empty($_POST['number'])){//numberが空の時
                	//データの挿入(ブラウザには表示しない)
                    $sql = $pdo -> prepare("INSERT INTO コメント一覧1 (name, comment, datetime, password) VALUES (:name, :comment, :datetime, :password)");
    
	                $name = $_POST['name'];
	                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	
                	$comment = $_POST['txt'];
                	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    
                    $datetime = date("Y-m-d H:i:s");
                	$sql -> bindParam(':datetime', $datetime, PDO::PARAM_STR);

                	$password = $_POST['password1'];
                	$sql -> bindParam(':password', $password, PDO::PARAM_STR);
	
                    $sql -> execute();
                }
                if(isset($_POST['number'])){//変更機能
                    $id = $_POST['number']; //変更する番号
                    $name = $_POST['name'];//変更後の値
                    $comment = $_POST['txt'];
                    $password = $_POST['password1'];
                    $sql = "UPDATE コメント一覧1 SET name=:name,comment=:comment,password=:password where id=:id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
            //削除機能
            if(isset($_POST['delete'])){
                //パスワード一致する時
                $sql = 'SELECT * FROM コメント一覧1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();   
                foreach ($results as $row){
                    if($row['id'] == $_POST['delete']){//idで選んで
                        if($row['password'] == $_POST['password2']){//passwordが一致する時
                            $id = $_POST['delete'];
                    	    $sql = 'delete from コメント一覧1 where id=:id';
	                        $stmt = $pdo->prepare($sql);
	                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                        $stmt->execute();
                        }
                    }
                }
            }
            
            //データの表示id,name,comment
            $sql = 'SELECT * FROM コメント一覧1';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();   
	        foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
		        echo $row['id'].',';
		        echo $row['name'].',';
		        echo $row['comment'].',';
		        echo $row['datetime'].'<br>';
                echo "<hr>";   
            }
        ?>
    </body>
</html>