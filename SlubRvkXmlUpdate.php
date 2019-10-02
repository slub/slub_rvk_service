<?php

class SlubRvkXmlUpdate {
    public $xmlPaths = ['xml' =>'./xml/', 'archive' => './xml/archive/'];
    public $oldFileName = 'rvko_current.xml';
    public $zipFile = './xml/rvko_xml.zip';
    public $newFile = '';
    public $newFileName = '';
    public $downloadSource = 'https://rvk.uni-regensburg.de/downloads/rvko_xml.zip';

    public function __construct(){
        if ( !is_dir($this->xmlPaths['xml']) ) {
            mkdir($this->xmlPaths['xml']); // create dir
        }
    }

    public function update(){
        $updateState = FALSE;
        $this->download($this->zipFile, $this->downloadSource);
        $this->newFile = $this->unzip($this->zipFile, $this->xmlPaths['xml']);
        if ($this->compare($this->xmlPaths['xml'].$this->oldFileName, $this->xmlPaths['xml'].$this->newFile['name'])){
            $this->archive($this->xmlPaths['xml'], $this->xmlPaths['archive'], $this->oldFileName, $this->newFile['name']);
            $updateState = TRUE;
            return $updateState;
        }

        return $updateState;
    }


    public function download($target, $source){
        //~ downloading latest rvk file
        file_put_contents($target, file_get_contents($source));
    }

    public function unzip($zipFile, $xml){
        //~ unzip and return of the new file's name
        $zip = new ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $files = $zip->statIndex(0);
            $zip->extractTo($xml);
            $zip->close();
        }
        return $files;
    }

    public function compare($oldFileName, $newFileName){
        //~ simple test if downloaded file is "newer"
        $state = FALSE;
        if(is_file($oldFileName)){
            if ( md5_file($oldFileName) != md5_file($newFileName) ){
                $state = TRUE;
            }
            else{
                echo 'The comparison has indicated that the new file is not newer than the old one.', PHP_EOL; flush();
            }
        }
        else{
            //~ when running first time, rvko_current.xml doesn't exist > now it does
            $state = FALSE;
            rename($newFileName, $oldFileName);
        }
        return $state;
    }

    public function archive($xml, $archive, $oldName, $newName){
        //~ create archive folder and move old file
        if ( !is_dir($archive) ) {
            mkdir($archive); // create dir
        }
        rename($xml.'/'.$oldName, $archive.'/rvko_old.xml');
        rename($xml.'/'.$newName, $xml.'/rvko_current.xml');
    }

}

//~ $start = new SlubRvkXmlUpdate();
?>
