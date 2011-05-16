<?php
require_once 'Upload.php';
$u=new Upload();
$u->uploaddir('img');

$u->upload();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" media="all" href="layout.css">
<title>File Upload</title>

</head>
<body>
		<header>
		
			<hgroup>
		
			</hgroup>


		</header>
		<nav>
		

		</nav>
		<section id="main">
		<fieldset>
		 <form id="Upload" action="index.php" enctype="multipart/form-data" method="post"> 
   
      <ol>
      <li>
    	  <label for="local">upload from your pc</label>
    	  <input id="local" type="file" name="file"> 
      </li>
      <li>
      <label for="remote">upload from URL</label>
      <input id="remote" type="text" size="37" name="link"> 
      </li>
      </ol>
      </fieldset>
      <fieldset>
      <button type="submit" name="submit" >upload</button>
      
      </fieldset>
      
    </form> 
    <?php $u->showlastImage();?>
     <?php $u->listImages(); ?>
		</section>

</html>