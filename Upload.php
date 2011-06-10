<?php
/**
 * upload file
 * @author ms
 * @todo: löschen
 *@todo icons
 U+2716 (10006)	✖
 */
class Upload{
	/**Attributes**/
	private $login=null;
	private $uploaddir;

	private $lastimage=null;

	private $ilegal=array('#','$','%','^','&','*','?', ' ');
	private $legal=array('no','dollar'	,'percent'	,'tilde','and','','','');

	/**__construct()**/

	public function __construct(Login $log=null){
		$this->login=$log;
	}//__construct
	public function upload(){
		$this->uploadfromPC();
		$this->uploadFromUrl();
	}
	public function delete($file){
		 return @unlink($file);
		
	}
	private function uploadfromPC(){
		if (@$_FILES['file']['name']){
			$this->fileHandle=$_FILES['file']['name'];
			$safe=str_replace($this->ilegal, $this->legal, $_FILES['file']['name']);
			$dest=$this->uploaddir.$safe;
			$source=$_FILES['file']['tmp_name'];
			$this->copy($source, $dest);
		}
	}

	private function uploadFromUrl(){
		$validUrl=@$_POST['link'];
		if(@$_POST['link'] && $this->fileExists(@$_POST['link'])&& $validUrl){
			$handle=fopen($_POST['link'], 'r');

			$safe=str_replace($this->ilegal, $this->legal, $_POST['link']);
			$safe=basename($safe);
			$dest=$this->uploaddir.$safe;
			$source=$_POST['link'];
			$this->copy($source, $dest);
		}
	}
	/**
	 *
	 * Copy uploadet file to the image folder
	 * @param PathToImage $source
	 * @param PathToImage $dest
	 */
	private function copy($source, $dest){
		$shaSource=sha1_file($source);
		$files=$this->listImages();

		//filename in use?
		$filenameInuse=false;
		if(in_array($dest,$files))
		$filenameInuse=true;
			
		//file exists?
		$exists=false;

		foreach ($files as $file){
			if(sha1_file($file) == $shaSource){
				$this->lastimage=$file;
				$exists=true;
				break;
			}
		}
		//filename is in use, but the image is a different one -
		if($filenameInuse && !$exists){
			$pathinfo=pathinfo(basename($dest));
			$dest=$this->uploaddir. $shaSource .'.'.  $pathinfo['extension'];

		}
		//file isn't on the machine
		if (!$exists){
			if(copy( $source, $dest)){
				$this->lastimage=$dest;
				return true;
			}
		}
		return false;
			
	}
	private function fileExists($url){
		/**
		 *
		 * check for remote upload
		 * file exists? 200 : 404
		 * @var unknown_type
		 */
		$AgetHeaders = @get_headers($url);
		if (preg_match("|200|", $AgetHeaders[0]))
		return true;
		return false;

	}

	public function showlastImage(){
		if ($this->lastimage){

			echo "<h1>last image</h1>";
			echo "<img src=\"{$this->lastimage}\"/ id=\"lastimage\"><p>";
		}
	}

	private static function sortByDate($a, $b){

	}
	private static function sortByImagesize($a,$b){
			
		$imgA=getimagesize($a);
		$imgB=getimagesize($b);
			
		$sumA=$imgA[0]* $imgA[1];
		$sumB=$imgB[0]* $imgB[1];
			
		if ($sumA == $sumB)
		return 0;
		return ($sumA <$sumB) ? -1 :1;
			
			
			
	}
	private function listImages(){
		$files=array();
		$handle=opendir($this->uploaddir());
		while (false !== ($file=readdir($handle))){
			if ($file !='.' && $file !='..')
			$files[]=$this->uploaddir().$file;

		}
		return $files;
	}
	public function showImages(){
		$files=$this->listImages();
		usort($files,array("Upload" ,"sortByImagesize"));
		$ctr=0;
		foreach($files as $file){
			#$full=$this->uploaddir().$file;
			$basename=basename($file);
			$imageurl='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/$file";
			$delete='';
			
			$linkToImage="\n<div class=\"linktoimage\"><a href=\"$file\">&#10546;</a></div>\n";
			if($this->login!=null &&$this->login->loggedIn()){
				$delete="\n<div><a href=\"index.php?delete=$file\">&#10006;</a></div>\n";
			}
			$copy="";
			$copy.="\n<div class=\"d_clip_button\" id=\"img$ctr\">&#9112;</div>\n";
			$copy.="<script>var d$ctr= new ZeroClipboard.Client();d$ctr.setText('$imageurl');d$ctr.setHandCursor(true);d$ctr.glue('img$ctr');</script>";
			
			
			echo "<div class=\"image\"><img alt=\"$file\" src=\"$file\"/><div class=\"image_wrapper\">$linkToImage  $copy $delete</div></div>\n";
			$ctr++;
		}
	}

	public function uploaddir($uploaddir=""){
		if (empty($uploaddir)){
			return $this->uploaddir;
		}else{
			if (substr($uploaddir, -1,1)!='/')
			$uploaddir.='/';
			$this->uploaddir=$uploaddir;
		}
	}
	private function isValidURL($url){
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}

}//class

?>