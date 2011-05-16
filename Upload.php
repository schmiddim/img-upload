<?php
class Upload{
	/**Attributes**/

	private $uploaddir;

	private $lastimage=null;

	private $ilegal=array(
		'#'
		,'$'
		,'%'
		,'^'
		,'&'
		,'*'
		,'?'
		);

		private $legal=array(
		'no'
		,'dollar'
		,'percent'
		,'tilde'
		,'and'
		,''
		,''
		);
		/**__construct()**/

		public function __construct(){

		}//__construct
		public function upload(){
			$this->uploadfromPC();
			$this->uploadFromUrl();
		}
		private function uploadfromPC(){
			if (@$_FILES['file']['name']){
				$this->fileHandle=$_FILES['file']['name'];
				$safe=str_replace($this->ilegal, $this->legal, $_FILES['file']['name']);
				$dest=$this->uploaddir.$safe;
				$source=$_FILES['file']['tmp_name'];
				if(copy($source, $dest)){
					$this->lastimage=$dest;
					return true;
				}
			}

		}
		private function uploadFromUrl(){

			if(@$_POST['link'] && $this->fileExists(@$_POST['link'])){
				$handle=fopen($_POST['link'], 'r');

				$safe=str_replace($this->ilegal, $this->legal, $_POST['link']);
				$safe=basename($safe);
				$dest=$this->uploaddir.$safe;

				if(copy( $_POST['link'], $dest)){
					$this->lastimage=$dest;
					return true;
				}
			}

		}
		private function fileExists($url){

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
		public function listImages(){
			$files=array();
			$handle=opendir($this->uploaddir());
			while (false !== ($file=readdir($handle))){
				if ($file !='.' && $file !='..')
				$files[]=$file;
					
			}

			foreach($files as $file){
				$full=$this->uploaddir().$file;
				echo "<img src=\"$full\"/>\n";
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

}//class

?>