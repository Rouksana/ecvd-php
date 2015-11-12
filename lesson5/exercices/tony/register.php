<?php session_start();

    require('connect.php');
    
    if($_POST["username"] && $_POST["password"]):

        $file = 'users.txt';
        // Une nouvelle personne à ajouter
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        
    
        $stmt = $conn->prepare('INSERT INTO users VALUES (null, :username, :email, :password)');

        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            
        $stmt->execute();


        $_SESSION["logged"] = true;
        $_SESSION["username"] = $_POST["username"];
        $_SESSION["email"] = $_POST["email"];

        header('Location: index.php');
        exit;
        
    else : ?>
       
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <title>Registration</title>
        </head>

        <body>
        <h1>Registration</h1>
        <form action="" method="post">

            <input type="text" placeholder="Username" name="username">
            <br>
            <input type="email" placeholder="Email" name="email">
            <br>
            <input type="password" placeholder="Password" name="password">
            <br>
            <br>
            <input type="submit" value="Register">

        </form>
        <br>
        <a href="index.php">Connexion</a>
        
    <?php endif; ?>

</body>

</html>