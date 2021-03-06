<?php require_once('requires/session.php');

if(!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

	require_once('requires/header.php');

	require_once('requires/functions.php');
	use Ecvdphp\User; 

	// Si l'utilisateur a demandé à supprimer son compte
	if(isset($_POST['delete'])) {
		
		try {
			$delete = $bdd->prepare("DELETE FROM `users` WHERE `username` = ?");
			$delete->execute(array($_SESSION['username']));
			header("Location: logout.php");
		} catch (Exception $e) {
			die("Couldn't delete your profile : ".$e);
		}
		
	} else if(isset($_POST['update'])) {

		if(!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

			try {
				$update = $bdd->prepare("UPDATE `users` SET `email`= ? WHERE `username` = ?");
				$update->execute(array($_POST['email'], $_SESSION['username']));
				echo "Votre email a bien été modifié !";
			} catch (Exception $e) {
				die("Some error occured while the updating process : ".$e);
			}

		} else {
			echo 'Rentrez un email valide.';
		}

	} else if(isset($_POST['upload'])) {
		$picture = User::checkFile();

		if(!is_array($picture)) {
			echo $picture;
		} else {
			echo User::moveFile($picture, 'profile');
		}
	}

	try {
		$response = $bdd->prepare("SELECT * FROM `users` LEFT JOIN `files` ON `users`.image_id = `files`.id WHERE `username` = ?");
		$response->execute(array($_SESSION['username']));
		$datas = $response->fetch();
	} catch (Exception $e) {
		die("Some error occured while looking for your profile : ".$e);
	}

?>

<a href="login.php">Retour</a>

<h2>Bienvenue sur votre page de profil <?= $_SESSION['username'] ?>!</h2>

<?php 

	if($datas['image_id'] !== null) { ?>
		<img width="200" src="<?=$datas['path']?><?=$datas['filename']?>" alt="" />
	<?php }
?>

<?= $datas['description'] ?>

<form action="profile.php" method="post">
	<input type="hidden" name="delete" />
	<button>Supprimer mon compte</button>
</form>

<br><br>

<form action="profile.php" method="post">
	<h2>Update E-Mail</h2>
	<input type="hidden" name="update" />

	<div>
		<label for="email">E-mail</label>
		<input type="email" name="email" />
	</div>

	<button>Mettre à jour</button>
</form>

<br><br>

<form action="profile.php" method="post" enctype="multipart/form-data">
	
	<h2>Update Profile Picture</h2>

	<input type="hidden" name="upload" value="true" />

	<div>
		<label for="filedata"></label>
		<input type="file" name="filedata" />
	</div>

	<div>
		<button>Uploader une image</button>
	</div>
</form>

<div>
	<a href="blog/">Accéder à mon blog</a>
</div>

<?php require_once('requires/footer.php'); ?>
