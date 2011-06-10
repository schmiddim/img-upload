<?php
require_once 'Login.php';
require_once 'Upload.php';
$login= new Login();


$login->validateLogin(@$_POST['user'], @$_POST['pass']);
$login->logout(@$_GET['logout']);
$u=new Upload($login);
$u->uploaddir('img');
if ($login->loggedIn())
	$u->upload();
	
	
if (isset($_GET['delete'])){
	echo "<h1>delete</h1>";
	$u->delete(@$_GET['delete']);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" media="all" href="layout.css">
<script type="text/javascript" src="libs/zeroclipboard/ZeroClipboard.js"></script>
<script>ZeroClipboard.setMoviePath( 'libs/zeroclipboard/ZeroClipboard10.swf' );</script>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">

<title>File Upload</title>

</head>
<body>
	<header></header>
	<nav></nav>

	<section id="main">
	<?php
	if ($login->loggedIn()){
		echo '
				<form id="upload" action="index.php" enctype="multipart/form-data" method="post">
				<fieldset>
				<legend>upload images <a href="index.php?logout=true">logout</a></legend>
				<ol>
					<li><label for="local">from pc</label> <input
						id="local" type="file" name="file">
					</li>
					<li><label for="remote">from url</label> <input id="remote"
						type="text" size="37" name="link">
					</li>
				</ol>
			</fieldset>
		
			<fieldset>
			<button type="submit" name="submit">upload</button>
			</fieldset>
			</form>
			';
	} else  {
		echo '
				<form id="login" action="index.php" method="post">
				<fieldset>
				<legend>login</legend>
				<ol>
					<li><label for="user">user</label> 
					<input id="user" type="text" name="user" size="20">
					</li>
					<li><label for="pass">pass</label>
					 <input id="pass"	type="password" size="20" name="pass">
					</li>
				</ol>
				</fieldset>
		
				<fieldset>
				<button type="submit" name="submit">login</button>
				</fieldset>
				</form>';
			
			}

			?>
			<?php $u->showlastImage();
			$u->showImages(); ?>
	</section>
	                 

</body>
</html>
