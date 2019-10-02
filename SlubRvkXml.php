
<?php

class SlubRvkXml {
    
    private $_xmlReader = null;
    
    public $config = [];
    
    public function __construct($config=[]) {
        $this->config = $config;
        if ( !is_dir($this->config['save']['path'] ) ) {
            mkdir($this->config['save']['path']); // create dir 
        }
        if ( !is_file($this->config['xmlSource']) ) {
            echo '@Error: download XML file from : https://rvk.uni-regensburg.de/regensburger-verbundklassifikation-online/rvk-download.',PHP_EOL,
                    ' and copy it to: ', $this->config['xmlSource'], PHP_EOL; flush();
                    exit();
        }
        $this->_xmlReader = new XMLReader();
        $this->_xmlReader->open($this->config['xmlSource']);
    }
            
    
    public function parseXml($rvk=[],$level=0) {
        while ($this->_xmlReader->read()) {
            if ( $this->_xmlReader->nodeType == XMLReader::ELEMENT ) {
                 switch ( $this->_xmlReader->name ) {
                    case 'node': 
                            // inner node  
                            if ( isset( $rvk['hierarchy'][$level] ) ) {
                                // remove hierarchy 
                                unset ( $rvk['hierarchy'][$level] );
                            }                        
                        
                            $rvk['notation'] = $this->_xmlReader->getAttribute( 'notation' );
                            $rvk['name'] = $this->_xmlReader->getAttribute( 'benennung' );
                            
                            if ( preg_match( $this->config['rvkRegex'], $rvk['notation'] ) ) {                                                         
                                $this->writeJson( $rvk );
                            } else {
                                echo '@Error: rvk not saved: ', $rvk['notation'], PHP_EOL; flush();
                            }
                        break;
                        
                    case 'children':
                            // go deeper in xml tree
                            $rvk['hierarchy'][$level] = $rvk;
                        
                            if ( isset( $rvk['hierarchy'][$level]['hierarchy'] ) ) {
                                // remove hierarchy from subtrees
                                unset ( $rvk['hierarchy'][$level]['hierarchy'] );
                            }
                            
                            $subLevel = $level + 1;
                            $state = $this->parseXml($rvk, $subLevel);
                        break;
                        
                    default:    
                        //echo '@Error: not parsed node: ', $this->_xmlReader->name, PHP_EOL;
                 }
                        
            }
            
            if ( $this->_xmlReader->nodeType == XMLReader::END_ELEMENT ) {
                if ( $this->_xmlReader->name == 'children' ) {
                    $this->_xmlReader->read(); // jump to next node, this is necessary otherwise the recursion will be terminated
                    return true;
                }
            }
        }
    }

    private function writeJson($rvk=[]) {
        if ( $rvk['notation'] ) {
                        
            $notationParts = explode(' ', $rvk['notation']);

            $file = []; // file data
            $file['path'] = $this->config['save']['path'] . $notationParts[0] . '/';
            $file['fileName'] = $notationParts[1] . $this->config['save']['fileType'];
            
            if (!is_dir ($file['path']) ) {
                mkdir($file['path']); // create dir 
            }
            
            $jsonContent = json_encode( $rvk , JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE ); // encode array
            $state = file_put_contents( $file['path'] . $file['fileName'] , $jsonContent ); // save json content

            if ( !$state ) {
                echo '@Error: can\'t write file: ', $file['path'] . $file['fileName'], PHP_EOL;
            }

        }
    }
}

?>