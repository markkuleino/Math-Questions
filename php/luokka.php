<?php
	class MySQL_naama {
		var $host_name = '';
		var $user_name = '';
		var $password = '';
		var $db_name = '';
		var $conn_id = 0; //Not actually a variable but an object or something
		var $errstr = '';
		var $halt_on_error = 1;
		var $query_pieces = array();
		var $result_id = 0;
		var $num_rows = 0;
		var $row = array();
		function connect() {
			$this->errno  = 0; #Tyhjää virhemuuttuja
			$this->errstr = '';
			if ( $this->conn_id == 0 ) // Yhdistä tietokantaan, jollei ole jo yhteydessä
			{
				try {
                    $this->conn_id = new PDO( 
                        "mysql:host=" . $this->host_name . 
                        ";dbname=" . $this->db_name . 
                        ";charset=utf8" .
                        "", $this->user_name, 
                        $this->password );
					//Persistent connections for faster db application 
				}
				catch ( PDOException $e ) {
					$this->error( $e->getMessage() );
				}
				return ( $this->conn_id );
			}
		}
		function disconnect() {
			if ( $this->conn_id != 0 ) {
				$this->conn_id = null;
			}
		}
		function error( $msg ) {
			if ( !$this->halt_on_error )
				return;
			$msg .= "\n";
			$this->errstr = $msg;
			echo "X1: VIRHE!" . $this->errstr . "</br>";
			// die (nl2br (htmlspecialchars ($msg)) );
			die();
		}
    }


	class Testi extends MySQL_naama {
		var $host_name = '';
		var $user_name = '';
		var $password = '';
		var $db_name = '';
		function __construct( $pwd ) {
			// Rakentaja. 
			$this->set_database( $pwd );
		}
		function set_database( $pwd ) {
			// Haetaan kone/ on internet or on localhost
			$url = "http://" . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
			//echo $url;
			if ( strlen( strstr( $url, "luntti.net" ) ) > 0 )
			{

                $config = parse_ini_file('../../backupPWD/config.ini'); 
        $connection = mysqli_connect($config['servername'],$config['username'],$config['password'],$config['dbname']);

				$this->host_name = $config['dbhost'];
				$this->user_name = $config['dbuser'];
				$this->password  = $config['dbpass'];
				$this->db_name   = $config['dbname'];
			}
			elseif ( strlen( strstr( $url, "localhost" ) ) > 0 )
			{
				$this->host_name = 'localhost';
				$this->user_name = 'root';
				$this->password  = ''; //$pwd;
                $this->db_name   = 'mathquestions';
				//echo "LOCALHOST"; 
			}
		}



    function   LisaaKuva($nimirnd, $hakemisto ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "INSERT INTO kuvat 
                    (nimirnd, hakemisto, lisayspvm)
                    VALUES 
                    (:nimirnd, :hakemisto, NOW()) 
                " );
                $sql->execute(array(
                    ':nimirnd' => $nimirnd,
                    ':hakemisto' => $hakemisto
                ));

                if ( $sql -> errorCode() > 0 ){
                  error_log(" -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-");
                  error_log ( print_r ($sql->errorInfo()) );
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
    }
    function   PaivitaKuvanTiedot($id, $kuvaaja, $tekijanoikeus){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "UPDATE kuvat 
                    SET kuvaaja=:kuvaaja,
					tekijanoikeus=:tekijanoikeus
					WHERE kuvaID=:id
                " );//WHERE
                //$sql->setFetchMode(PDO::FETCH_INTO, new aiheet); 
                $sql->execute(array(
                    ':id' => $id,
                    ':kuvaaja' => $kuvaaja,
                    ':tekijanoikeus' => $tekijanoikeus
                ));

                if ( $sql -> errorCode() > 0 ){
                  error_log(" -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-");
                  error_log ( print_r ($sql->errorInfo()) );
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
    }

    function   HaeKokeenEliotID( $koeID, $maxLkm ){
		if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * from kokeenEliot
					WHERE koeID = :koeID
					ORDER BY rand()
					LIMIT $maxLkm
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":koeID" => $koeID
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}
	
	

    function   HaeKuvienIndeksit(){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM kuvat ORDER by kuvaID DESC
                    LIMIT 15
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new kuva);
                if( !$sql->execute(array(
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}

	function lisaaKoe( $kokeenNimi, $luokkaAste, $MaxLkm, $ohje ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
				try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO kokeet (luokkaAste,MaxLkm, ohje, nimi) VALUES(:luokkaAste,:MaxLkm, :ohje, :kokeenNimi)
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new koe);
                if( !$sql->execute(array(
					":kokeenNimi" => $kokeenNimi,
					":luokkaAste" => $luokkaAste,
					":MaxLkm" => $MaxLkm,
					":ohje" => $ohje
                )) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $this -> conn_id -> lastInsertId();
	}  





    function login($user, $pwd){

        if (empty($this->conn_id))
            $this->connect();
        try {
            $sql = $this->conn_id->prepare("SELECT ID, name  FROM users 
            WHERE name = :userid AND pwd = :password LIMIT 1");

            $sql->setFetchMode(PDO::FETCH_INTO, new kayttaja);
            $sql->execute(array(
                ':userid' => $user,
                ':password' => $pwd
            ));
            $result = $sql->fetchAll();
            //print_r($result);
        }
        catch (PDOException $e) {
            $this->error($e->getMessage());
        }
        return $result;
    }




	
   function   getLevels( ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM levels
					 ORDER BY ID 
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new koe);
                if( !$sql->execute(array(
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
    }
    function   getRefs( ){
        if ( empty( $this->conn_id ) ) // Not connected
            $this->connect();
        try{
            $sql = $this->conn_id->prepare( "
                SELECT * FROM refs
                 ORDER BY ref 
            " );//WHERE
            $sql->setFetchMode(PDO::FETCH_INTO, new koe);
            if( !$sql->execute(array(
            )) ){
            print_r($sql->errorInfo());
    }
    if ($sql -> rowCount() < 2){
                    $result = $sql -> fetchAll();
            } else{
                while ($object = $sql->fetch()) {
                    $result[] = clone $object;
               }  
            }
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    return $result;
}
function   getTopicsQ( ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
            SELECT topic FROM topicsQ
             ORDER BY topic 
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
        )) ){
        print_r($sql->errorInfo());
}
if ($sql -> rowCount() < 2){
                $result = $sql -> fetchAll();
        } else{
            while ($object = $sql->fetch()) {
                $result[] = clone $object;
           }  
        }
    } catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $result;
}

function   getRef( $ref ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
            SELECT ID FROM refs
             WHERE ref=:ref 
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":ref" => $ref
        )) ){
        print_r($sql->errorInfo());
}
if ($sql -> rowCount() < 2){
                $result = $sql -> fetchAll();
        } else{
            while ($object = $sql->fetch()) {
                $result[] = clone $object;
           }  
        }
    } catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $result;
}



function addRef( $ref ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO refs (ref) VALUES(:ref)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":ref" => $ref
        )) ){
        print_r($sql->errorInfo());
        }
    }catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $this -> conn_id -> lastInsertId();
}  



function   getTopicQ( $ref ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
            SELECT ID FROM topicsQ
             WHERE topic=:ref 
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":ref" => $ref
        )) ){
        print_r($sql->errorInfo());
}
if ($sql -> rowCount() < 2){
                $result = $sql -> fetchAll();
        } else{
            while ($object = $sql->fetch()) {
                $result[] = clone $object;
           }  
        }
    } catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $result;
}



function addTopicQ( $ref ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO topicsQ (topic) VALUES(:ref)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":ref" => $ref
        )) ){
        print_r($sql->errorInfo());
        }
    }catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $this -> conn_id -> lastInsertId();
}  


function addQuestion( $q, $qdate, $nro, $link, $refID ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO questions (question, qdate, date, questionNRO, link, refID) 
            VALUES(:q, :qdate, NOW(), :nro, :link, :refID)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":q" => $q, 
            ":qdate" => $qdate,
            ":nro" => $nro, 
            ":link" => $link, 
            ":refID" => $refID
        )) ){
        print_r($sql->errorInfo());
        }
    }catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $this -> conn_id -> lastInsertId();
}  




function addTopicQuestion( $qID, $tID ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO questionTopics (questionID, topicID) VALUES(:qID, :tID)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":qID" => $qID,
            ":tID" => $tID
        )) ){
        print_r($sql->errorInfo());
        }
    }catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $this -> conn_id -> lastInsertId();
}  

   function   getQuestionTopics( ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                select distinct topic from questiontopics 
                inner join topicsQ on questiontopics.topicID=topicsQ.ID
                ORDER by topic;
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new koe);
                if( !$sql->execute(array(
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
    }
    
    
   function   getNumberOfQuestions( ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                select count(*) as lkm from questions;
				" );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new kuva);
                if( !$sql->execute(array(
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}	
	
    
    function   getQuestions( ){
        if ( empty( $this->conn_id ) ) // Not connected
            $this->connect();
        try{
            $sql = $this->conn_id->prepare( "
            select *  from questions;
            " );//WHERE
            $sql->setFetchMode(PDO::FETCH_INTO, new kuva);
            if( !$sql->execute(array(
            )) ){
            print_r($sql->errorInfo());
    }
    if ($sql -> rowCount() < 2){
                    $result = $sql -> fetchAll();
            } else{
                while ($object = $sql->fetch()) {
                    $result[] = clone $object;
               }  
            }
        } catch (PDOException $e) {
            $this->error($e->getMessage());
        }
    return $result;
}	





    
	function lisaaKoeElio( $koeID, $elioID ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
				try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO kokeenEliot (koeID, elioID) VALUES(:koeID,:elioID)
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new koe);
                if( !$sql->execute(array(
					":koeID" => $koeID,
					":elioID" => $elioID
                )) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $this -> conn_id -> lastInsertId();
	}  
	
	
	function lisaaElio( $nimi, $tieteellinen ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO eliot (nimi, tieteellinen) VALUES(:nimi, :tieteellinen)
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":nimi" => $nimi,
					":tieteellinen" => $tieteellinen
                )) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }
			
		return $this -> conn_id -> lastInsertId();
	}  
		

	
   
	function haeElio( $nimi ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM eliot WHERE nimi=:nimi
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":nimi" => $nimi 
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}
	function haeElioId( $id ){
		if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM eliot WHERE ID=:id
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":id" => $id 
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result[0];
	}

	function haeEliot( ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM eliot ORDER by nimi
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}
	

		function haeElionKuva( $elioID ){
		if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM kuvat 
					INNER JOIN eliokuvat 
					ON eliokuvat.kuvaID = kuvat.kuvaID
					WHERE eliokuvat.elioID = :elioID
					ORDER BY rand()
					LIMIT 1
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":elioID" => $elioID
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}	
	
	
	function haeKuvaElio( $elioID, $kuvaID ){
		if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM eliokuvat 
					WHERE kuvaID=:kuvaID and elioID = :elioID
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":kuvaID" => $kuvaID,
					":elioID" => $elioID
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}	
	
	function lisaaKuvaElio( $elioID, $kuvaID ){
           if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO eliokuvat (elioID, kuvaID) VALUES(:elioID, :kuvaID)
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new elio);
                if( !$sql->execute(array(
					":elioID" => $elioID,
					":kuvaID" => $kuvaID 
					)) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }
			
		return $this -> conn_id -> lastInsertId();
				
	}  


	
	
	
	
	function haeTehtavat( ){
            if ( empty( $this->conn_id ) ) // Not connected
			$this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM tehtavat ORDER by nimi
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new tehtava);
                if( !$sql->execute(array(
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}	
	function haeTehtava($id ){
            if ( empty( $this->conn_id ) ) // Not connected
			$this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM tehtavat WHERE tehtavaID = :id
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new tehtava);
                if( !$sql->execute(array(
					':id' => $id
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result[0];
	}
	
	
	function lisaaVastaaja($nimi,$lempinimi, $luokka, $koeID, $satID ){
		if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO vastaajat 
					(nimi,lempinimi,luokka,koeID,satID, pvm)
					VALUES
					(:nimi,:lempinimi,:luokka,:koeID,:satID, NOW())
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new vastaaja);
                if( !$sql->execute(array(
					":nimi" => $nimi,
					":lempinimi" => $lempinimi,
					":luokka" => $luokka,
					":koeID" => $koeID,
					":satID" => $satID
                )) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }
			
		return $this -> conn_id -> lastInsertId();
				
	}  	
	
	function lisaaPisteet( $vastaajaID, $pisteet ){
	if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO pisteet
					(vastaajaID, pisteet)
					VALUES
					(:vastaajaID, :pisteet)
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new vastaaja);
                if( !$sql->execute(array(
					":vastaajaID" => $vastaajaID,
					":pisteet" => $pisteet,
                )) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }			
		return $this -> conn_id -> lastInsertId();
	} 		


	
	
	
	      function haeSijoitus($koeID, $pisteet){
			  if ( empty( $this->conn_id ) ) // Not connected
		  $this->connect();
          try{
                $sql = $this->conn_id->prepare( "
					select COUNT(*) AS sijoitus from pisteet 
					INNER join vastaajat
					on vastaajat.ID = pisteet.vastaajaID
					WHERE vastaajat.koeID = :koeID
					AND pisteet >= :pisteet					
				" );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new kuva);
                if( !$sql->execute(array(
                      ':koeID' => $koeID,
					  ':pisteet' => $pisteet
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}

	
	

	
	
      function haePisteet($koeID){
          if ( empty( $this->conn_id ) ) // Not connected
		  $this->connect();
          try{
                $sql = $this->conn_id->prepare( "
					select vastaajat.lempinimi, pisteet.pisteet, vastaajat.luokka from pisteet 
					INNER join vastaajat
					on vastaajat.ID = pisteet.vastaajaID
					WHERE vastaajat.koeID = :koeID
					order by pisteet.pisteet DESC
					limit 8
				" );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new kuva);
                if( !$sql->execute(array(
                      ':koeID' => $koeID
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}
	
	
	

	
	function lisaaVastaus($vastaus,$vastaajaID,$elioID,$aika,$kuvaID){
		if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                    INSERT INTO vastaukset 
					(vastaus,vastaajaID,elioID,aika,kuvaID)
					VALUES
					(:vastaus,:vastaajaID,:elioID,:aika,:kuvaID)
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new vastaaja);
                if( !$sql->execute(array(
					":vastaus" => $vastaus,
					":vastaajaID" => $vastaajaID,
					":elioID" => $elioID,
					":aika" => $aika,
					":kuvaID" => $kuvaID				
				)) ){
				print_r($sql->errorInfo());
				}
			}catch (PDOException $e) {
                $this->error($e->getMessage());
            }
			
		return $this -> conn_id -> lastInsertId();
				
	}  	


/*
CREATE TABLE vastaukset(
  ID  SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  vastaajaID INT UNSIGNED, 
  vastaus VARCHAR(100),
  elioID SMALLINT UNSIGNED,
  kuvaID SMALLINT UNSIGNED,
  aika SMALLINT UNSIGNED,
  FOREIGN KEY (kuvaID) REFERENCES kuvat(kuvaID),
  FOREIGN KEY (elioID) REFERENCES eliot(ID),
  FOREIGN KEY (vastaajaID) REFERENCES vastaajat(ID),	
  PRIMARY KEY (ID)
)ENGINE=InnoDB DEFAULT CHARSET=utf8; 
*/

	
	
	
//http://stackoverflow.com/questions/210564/getting-raw-sql-query-string-from-pdo-prepared-statements
/**
 * Replaces any parameter placeholders in a query with the value of that
 * parameter. Useful for debugging. Assumes anonymous parameters from 
 * $params are are in the same order as specified in $query
 *
 * @param string $query The sql query with parameter placeholders
 * @param array $params The array of substitution parameters
 * @return string The interpolated query
 */
public static function interpolateQuery($query, $params) {
    $keys = array();

    # build a regular expression for each parameter
    foreach ($params as $key => $value) {
        if (is_string($key)) {
            $keys[] = '/:'.$key.'/';
        } else {
            $keys[] = '/[?]/';
        }
    }

    $query = preg_replace($keys, $params, $query, 1, $count);

    #trigger_error('replaced '.$count.' keys');

    return $query;
}



       function haeKuvanElio($kuvaID){
          if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
          try{
                $sql = $this->conn_id->prepare( "
                    SELECT * FROM eliot
                    INNER JOIN eliokuvat
                    on eliot.ID = eliokuvat.elioID
                    WHERE kuvaID=:kuvaID
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new kuva);
                if( !$sql->execute(array(
                      ':kuvaID' => $kuvaID
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}



       function haeTagitKuvasta($kuvaID, $tagi){
          if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
          try{
                $db = $tagi . "kuva";

                $sql = $this->conn_id->prepare( "
                    SELECT * FROM $db 
                    INNER JOIN $elio
                    on elioID = $elio.ID
                    WHERE kuvaID=:kuvaID
                    ORDER by kuvaus
                " );//WHERE
                $sql->setFetchMode(PDO::FETCH_INTO, new tagi);
                if( !$sql->execute(array(
                      ':kuvaID' => $kuvaID
                )) ){
   		 	print_r($sql->errorInfo());
		}
		if ($sql -> rowCount() < 2){
                    	$result = $sql -> fetchAll();
                } else{
                    while ($object = $sql->fetch()) {
                        $result[] = clone $object;
                   }  
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
		return $result;
	}






       function lisaaTagiKuvaan($kuvaID, $tagi, $tagiID){
          if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
          try{
                $db = $tagi . "kuva";
                $row = $tagi . "ID";
                $sql = $this->conn_id->prepare( "INSERT INTO  $db  (kuvaID, elioID)
                    SELECT  :kuvaID, :elioID 
                    FROM dual
                    WHERE NOT EXISTS ( SELECT *
                       FROM $db
                       WHERE elioID=:elioID
                        AND  kuvaID=:kuvaID )
                " );//WHERE


                $sql->execute(array(
                    ':kuvaID' => $kuvaID,
                    ':elioID' => $elioID
                ));

                if ( $sql -> errorCode() > 0 ){
                  error_log(" -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-");
                  error_log ( print_r ($sql->errorInfo()) );
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
    }



	
    }  

    class kuva{
       function Kirjoitalinkki(){
           $link = $this -> ytlinkki; 
           if (!strpos( $link, "http://" ) ) {
              $link = "http://" . $link ; 
           }
           return $link; 

        }
   
    }


    class aiheet{}
    class tagi{
       public $ID=0;
       public $kuvaus='';
       public $checked='';
    }
	class elio{}
	class koe{}
	class vastaaja{}
	
	
    class materiaalit{}
    class kayttaja{}
    class tapahtuma{
        function alkupvm(  ){
            return date( 'd.m.Y' , strtotime( $this -> pvm ));
        }
        function alkuaika(  ){
            return date( 'H.i' , strtotime( $this -> pvm ));
        }
        function alkutunti(  ){
            return date( 'H' , strtotime( $this -> pvm ));
        }
        function alkumin(  ){
            return date( 'i' , strtotime( $this -> pvm ));
        }
        function loppupvm(  ){
            return date( 'd.m.Y' , strtotime( $this -> loppupvm ));
        }
        function loppuaika(  ){
            return date( 'H.i' , strtotime( $this -> loppupvm ));
        }
        function lopputunti(  ){
            return date( 'H' , strtotime( $this -> loppupvm ));
        }
        function loppumin(  ){
            return date( 'i' , strtotime( $this -> loppupvm ));
        }

        function lisaakuva( $arg ){
            if ( !empty( $this -> kuvanimi )){

                echo '<img ' . $arg  .   '  src="kalenterikuvat/' . $this -> tmpnimi . '">';
            }
        }

    }
    
    

?>
