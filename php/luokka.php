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

                $config = parse_ini_file ( $_SERVER['DOCUMENT_ROOT'] . '/../backupPWD/config.ini'); 

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



    function   addImage($namernd ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "INSERT INTO images 
                    (namernd,  date)
                    VALUES 
                    (:namernd, NOW()) 
                " );
                $sql->execute(array(
                    ':namernd' => $namernd
                ));

                if ( $sql -> errorCode() > 0 ){
                  error_log(" -+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-");
                  error_log ( print_r ($sql->errorInfo()) );
                }
            } catch (PDOException $e) {
                $this->error($e->getMessage());
            }
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


    function   getImages( ){
        if ( empty( $this->conn_id ) ) // Not connected
            $this->connect();
        try{
            $sql = $this->conn_id->prepare( "
                SELECT * FROM images
                 ORDER BY date DESC 
            " );//WHERE
            $sql->setFetchMode(PDO::FETCH_INTO, new images);
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
    function getRefs( ){
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
function getRefsUsed( ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
        select refs.ID, ref from questions 
        INNER JOIN
        refs on questions.refID = refs.ID
        GROUP by refs.ID  
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



function   getTopics( ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
            SELECT topic FROM `topicsA` 
            UNION
            SELECT topic from topicsQ
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
function   getRefID( $id ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
            SELECT * FROM refs
             WHERE ID=:id 
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
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
function   getTopicA( $ref ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
            SELECT ID FROM topicsA
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


function   getSolutionTopics( $sid ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
        select  topicsA.topic from topicsA
        INNER JOIN 
          solutionTopics  on solutionTopics.topicID = topicsA.ID
        INNER JOIN 
          solutions on solutions.ID = solutionTopics.solutionID        
        WHERE solutions.ID=:sid 
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":sid" => $sid
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
function addTopicA( $ref ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO topicsA (topic) VALUES(:ref)
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


function addQuestion( $q, $qdate, $nro, $link, $refID, $level ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO questions (question, qdate, date, questionNRO, link, refID, levelID) 
            VALUES(:q, :qdate, NOW(), :nro, :link, :refID, :level)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":q" => $q, 
            ":qdate" => $qdate,
            ":nro" => $nro, 
            ":link" => $link, 
            ":refID" => $refID,
            ":level" => $level
        )) ){
        print_r($sql->errorInfo());
        }
    }catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $this -> conn_id -> lastInsertId();
}  


function addSolution( $s, $qID ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO solutions (solution, date, qID ) 
            VALUES(:s, NOW(), :qID)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":s" => $s,
            ":qID" => $qID,
        )) ){
        print_r($sql->errorInfo());
        }
    }catch (PDOException $e) {
        $this->error($e->getMessage());
    }
return $this -> conn_id -> lastInsertId();
}  


function addTopicSolution( $sID, $tID ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
        try{
        $sql = $this->conn_id->prepare( "
            INSERT INTO solutionTopics (solutionID, topicID) VALUES(:sID, :tID)
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
        if( !$sql->execute(array(
            ":sID" => $sID,
            ":tID" => $tID
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
function   getQuestionTopicsOne( $id ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
        select  topicsQ.topic as topic from topicsQ
INNER JOIN
questionTopics on questionTopics.topicID = topicsQ.ID
WHERE 
questionTopics.questionID = :id
ORDER BY topicsQ.topic
        " );//WHERE
        $sql->setFetchMode(PDO::FETCH_INTO, new koe);
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
return $result;
}
   function   getQuestionTopics( ){
            if ( empty( $this->conn_id ) ) // Not connected
                $this->connect();
            try{
                $sql = $this->conn_id->prepare( "
                select distinct topic from questionTopics 
                inner join topicsQ on questionTopics.topicID=topicsQ.ID
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
    function   getAllSolutionTopics( ){
        if ( empty( $this->conn_id ) ) // Not connected
            $this->connect();
        try{
            $sql = $this->conn_id->prepare( "
            select distinct topic from solutionTopics 
            inner join topicsA on solutionTopics.topicID=topicsA.ID
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
                $sql->setFetchMode(PDO::FETCH_INTO, new question);
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
            select ID as questionID, qdate as qdate, date as date, questionNRO as questionNRO, link as link, refID as refID, question as question  from questions;
            " );//WHERE
            $sql->setFetchMode(PDO::FETCH_INTO, new question);
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

function   getQuestion( $id ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
        select *  from questions
        WHERE ID = :id
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
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
return $result;
}	


function getTaggedQuestions( $st ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $in  = str_repeat('?,', count($st) - 1) . '?';
 
        $sql = $this->conn_id->prepare( "
        select * from questions 
        INNER JOIN
        questionTopics on questions.ID = questionTopics.questionID
        INNER JOIN
        topicsQ on questionTopics.topicID = topicsQ.ID
        WHERE 
        topicsQ.topic IN ($in)
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
        if( !$sql->execute( $st ) ){
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

function getTaggedQuestionsAdv( $wh, $whIn ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{

        $sql = $this->conn_id->prepare( $wh );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
        if( !$sql->execute( $whIn ) ){
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









function getTaggedQuestionsAND( $st ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $in  = str_repeat('?,', count($st) - 1) . '?';


        $sql = $this->conn_id->prepare( "
        select * from questions 
        INNER JOIN
        questionTopics on questions.ID = questionTopics.questionID
        INNER JOIN
        topicsQ on questionTopics.topicID = topicsQ.ID
        WHERE 
        topicsQ.topic IN ($in)
        group by questions.ID
		having count(*) > 1
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
        if( !$sql->execute( $st ) ){
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


function getTaggedSolutionQuestions( $st ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $in  = str_repeat('?,', count($st) - 1) . '?';

        //, qdate as qdate, questions.date as date, questionNRO as questionNRO, link as link, /refID as refID, question as question 
 
        $sql = $this->conn_id->prepare( "
        select  questions.ID as questionID
        from questions 
        INNER JOIN
        solutions on solutions.qID = questions.ID
        INNER JOIN
        solutionTopics on solutionTopics.solutionID = solutions.ID 
        INNER JOIN
        topicsA on topicsA.ID = solutionTopics.topicID
        WHERE
        topicsA.topic IN ($in)
        GROUP BY questions.ID
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
        if( !$sql->execute( $st ) ){
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

function getTaggedSolutionQuestionsAND( $st ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $in  = str_repeat('?,', count($st) - 1) . '?';
 
        $sql = $this->conn_id->prepare( "
        select DISTINCT questions.ID as ID, qdate as qdate, questions.date as date, questionNRO as questionNRO, link as link, refID as refID, question as question from questions 
        INNER JOIN
        solutions on solutions.qID = questions.ID
        INNER JOIN
        solutionTopics on solutionTopics.solutionID = solutions.ID 
        INNER JOIN
        topicsA on topicsA.ID = solutionTopics.topicID
        WHERE
        topicsA.topic IN ($in)
        group by questions.ID
        having count(*) > 1
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
        if( !$sql->execute( $st ) ){
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






function   getSolutions( $id ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
        select *  from solutions
        WHERE qid = :id
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
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
return $result;
}	



function getMinYear( ){
    if ( empty( $this->conn_id ) ) // Not connected
        $this->connect();
    try{
        $sql = $this->conn_id->prepare( "
        SELECT year( min( qdate ) ) AS year FROM `questions` WHERE qdate > 0
        " );
        $sql->setFetchMode(PDO::FETCH_INTO, new question);
        if( !$sql->execute() ){
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


	
    }  

    class references{
        public $conn;
        function setDB( $conn ){
            $this -> conn = $conn;
        }

        function getRefs(){
            return $this-> conn-> getRefs();
        }

    }



    class question{
        public $conn;
        function setDB( $conn ){
            $this -> conn = $conn;
        }

        function getLevels(){
            return $this-> conn-> getLevels();
        }


        function getMinYear(){
            return $this-> conn-> getMinYear()[0];
        }

        //
        // Printing functions
        //


        function imageString( $a ){
            $str = '';
            $str .= '<div class="row"><div class="three columns">';
            $str .= '<a href="' . $a . '">';
            $str .= '<img class="u-full-width" src="' . $a . '">';
            $str .= '</a>';
            $str .= '</div></div>';
            return $str;

        } 


        function printQuestion( $id ){
            $q = $this->conn -> getQuestion( $id );

            if ( count($q) == 0 ){
                $str =  "Not a valid ID";
                return $str;
            }

            $str = "";
            // Ref
            $r = $this->conn-> getRefID( $q[0]->refID );
            //$str .= '<div class="reference">';
            //$str .= $r[0] -> ref;
            //$str .= '</div>';


            // The question
            $str .= '<div class="question">';
            $str .= '<b>' . $q[0] -> questionNRO . " ";
            $str .= $r[0] -> ref . '</b>';

            //strtr ($str, array ('a' => '<replacement>'));
            $str .= $q[0] -> question;

            //$str .= strtr( $q[0] -> question, array('htmlimage' => '$this->HHhtmlImage') );

            // Tips
            $qTopics = $this->conn -> getQuestionTopicsOne( $id );
            if ( count($qTopics)>0 ){
                $str .= '<div class="topics">';
                $str .= '<a href="#" class="toggle_solutions">Vihje</a> ';
                $str .= '<div class=" nonvisible">';
                foreach ($qTopics as $t){
                    $str .= '<span class="qtopic">';
                    $str .= ( $t -> topic );
                    $str .= '</span>';
                }
                $str .= '</div>';
                $str .= '</div>';
            }

            //Question DIV
            $str .= '</div>';


            $str .= "\n\n";
            
            //
            // Solutions
            $solutions = $this -> conn -> getSolutions( $id );
            if (count( $solutions) > 0){          
                //$str .= "<div class='solutions'>Ratkaisut</div>"; 
          
                $solutionNumber = 1;
                foreach( $solutions as $s){
                    $str .= '<a href="#" class="toggle_solutions large">';
                    $str .= $solutionNumber .'</a> ' . "\n";

                    $solutionNumber = $solutionNumber +1;
            
                    $solTopics = $this -> conn -> getSolutionTopics( $s -> ID );


                    $str .= '   <div class=" nonvisible">'. "\n";
                    foreach ($solTopics as $t){
                        $str .= '    <span class="topic">';
                        $str .= ( $t -> topic );
                        $str .= '   </span>';
                    }
                    $str .= "\n";


            
                    $str .= ' <div >'. "\n";
                    $str .= '  <a href="#" class="toggle_solutions">Näytä ratkaisu</a> '. "\n";
/*                     $str .= '   <div class=" nonvisible">'. "\n";
                    foreach ($solTopics as $t){
                        $str .= '    <span class="topic">'. "\n";
                        $str .= ( $t -> topic ) . "\n";
                        $str .= '   </span>'. "\n";
                    }
                    $str .= '  </div>'. "\n";
 */                    $str .= ' </span>'. "\n";
                    //$str .= '</div>'. "\n";

                    $str .= '<div class="solution nonvisible">'. "\n";
                    $str .=  $s -> solution; 
                    $str .= '</div>' . "\n" ;
                    $str .= '  </div>'. "\n";
                    $str .= '</div>';
                    $str .= "</br>";
                }
            }
          
          


            //print_r ( $q );

            //
            //
            //
            return $str;
        }


        function printPageNumbers( $q, $s ){
            $str = '<div class="pages">';
            $page = 0;

            $arr = $_GET;
            if (isset( $arr['page'])){
                unset($arr['page']);
            }


            for ( $i = 10; $i <= count($q)+10; $i+=10){

                if ( $page != $s -> page){
                    $str .= "<a href='?page=" . $page .  "&";    
                    $str .= http_build_query( $arr );
                    $str .= "'>";
                }
                if ( $i > count($q)){
                    $str .= strval($i-9) . "&mdash;" . strval(count($q));                
                }else{
                    $str .= strval($i-9) . "&mdash;" . strval($i) . ", ";                
                }
                if ( $page != $s -> page){
                    $str .= '</a>';
                }

                $page +=1;
            }
            $str .= '</div>';
            return $str;

        }

        //
        //
        //

        function findQuestionsQ($s){
 
            $sel = "select questions.ID AS questionID from questions 
            INNER JOIN
            questionTopics on questions.ID = questionTopics.questionID
            INNER JOIN
            topicsQ on questionTopics.topicID = topicsQ.ID ";
            // "WHERE ";

            $wh = [];
            $whIn = [];
            if ( count( $s-> searchTopics ) > 0 ){
                $in  = str_repeat('?,', count($s->searchTopics) - 1) . '?';
                array_push( $wh, " ( topicsQ.topic IN ($in) )" );
                $whIn = $s->searchTopics;
            }
            //print_r($whIn);

            if ( strcmp( $s -> searchAdvanced, "on")==0 ){
                if ( count( $s->searchRefsID )>0 ){
                    $in  = str_repeat('?,', count($s->searchRefsID) - 1) . '?';
                    array_push( $wh, " ( refID IN ($in) )");
                    //print_r( $s->searchRefsID );
                    $whIn = array_merge($whIn, $s->searchRefsID );
                }

                if ( $s->searchIncludeNoDate == 1){
                    if ( $s->searchYearFromQ >0  &&  $s->searchYearToQ >0  ){
                        array_push( $wh, " ( ( YEAR(qdate) BETWEEN ? AND ? ) OR YEAR(qdate) IS NULL )");
                        array_push($whIn, $s->searchYearFromQ );
                        array_push($whIn, $s->searchYearToQ );
                    }elseif (  $s->searchYearFromQ > 0 ){
                        array_push( $wh, " ( ( YEAR(qdate) > ? ) OR YEAR(qdate) IS NULL) ");
                        array_push($whIn, $s->searchYearFromQ );
                    }elseif (  $s->searchYearToQ > 0 ){
                        array_push( $wh, " ( ( YEAR(qdate) < ? ) OR YEAR(qdate) IS NULL) ");
                        array_push($whIn, $s->searchYearToQ );
                    }

                }else{
                    if ( $s->searchYearFromQ >0  &&  $s->searchYearToQ >0  ){
                        array_push( $wh, " ( YEAR(qdate) BETWEEN ? AND ? )");
                        array_push($whIn, $s->searchYearFromQ );
                        array_push($whIn, $s->searchYearToQ );
                    }elseif (  $s->searchYearFromQ > 0 ){
                        array_push( $wh, " ( YEAR(qdate) > ? )");
                        array_push($whIn, $s->searchYearFromQ );
                    }elseif (  $s->searchYearToQ > 0 ){
                        array_push( $wh, " ( YEAR(qdate) < ? )");
                        array_push($whIn, $s->searchYearToQ );
                    }
                }

                if (count( $s->searchLevelsID)>0 ){
                    $in  = str_repeat('?,', count($s->searchLevelsID) - 1) . '?';
                    if ($s->searchIncludeNoLevels == 1){
                        array_push( $wh, " ( levelID IN ($in) OR levelID IS NULL )");
                    }else{
                        array_push( $wh, " ( levelID IN ($in) )");
                    }
                //print_r( $s->searchRefsID );
                    $whIn = array_merge($whIn, $s->searchLevelsID );                
                }
            }

            if ( count( $wh ) > 0){
                $sel .= " WHERE " . implode(" AND ", $wh);
            }
            $sel .= " GROUP BY questions.ID ";
            $sel .= " ORDER BY date desc";
            //echo '<pre>';
            //print_r( $wh );
            //echo $sel . "</br>";
            //print_r($whIn);
            //echo '</pre>';

            return $this -> conn -> getTaggedQuestionsAdv($sel, $whIn);
        
           

        }

        function findQuestionsS($s){
            return $this -> conn -> getTaggedSolutionQuestions($s->searchTopicsSolution);
        }

        //
        //
        //

        function removeDuplicate( $arr ){
            $ids = [];
            foreach( $arr as $a => $aa ){
                if (!in_array( $aa -> questionID, $ids)){
                    array_push($ids, $aa -> questionID);
                }else{
                    //Remove the element in array
                    unset($arr[$a]);
                }
                //echo( $aa -> questionID );
            }
            //print_r($ids);
            //print_r($arr);
            return $arr;
        }
        function combineAND($arr1, $arr2){
            //echo '<pre>';
            //echo "Arr1:</br>" ;
            //print_r($arr1);
            //echo "Arr2:</br>" ;
            //print_r($arr2);
            //$arr = [];
            foreach( $arr1 as $a1){
                //echo "arr1: " . $a1->questionID; 
                foreach( $arr2 as $a2){
                    //echo "arr2: " . $a2->questionID;
                    if ( $a1->questionID == $a2->questionID){
                        array_push($arr, $a1->questionID);                        
                    } 
                }
            }
            //echo '</pre>';
            return $arr;

        }


    }
    class answer{
    }

    class searchVar{
        // Example:
        // ?q%5B%5D=Alkunopeus&q%5B%5D=Asukasluku&
        // s%5B%5D=identtisesti+tosi
        // boolean_topic=or&
        // boolean_both=or&
        // boolean_solution=or&
        //
        // advanced=on
        // r%5B%5D=Baltian+tie&r%5B%5D=Catriona+Shearer&r%5B%5D=Putnam
        // includeNoDate=1
        // yearFromQ=1996&
        // yearToQ=1996&
        // includeNoLevels=1
        // l%5B%5D=Alakoulu&l%5B%5D=Yl%C3%A4koulu
        private $conn;
        private $refs;


        public $searchRefsID = [];
        private $searchRefs = [];

        public $searchAdvanced = 0;
        public $searchAdvancedchecked = "";

        public $searchYearFromQ = 0;
        public $searchYearToQ = 0;
        public $searchIncludeNoDate=1;
        public $searchIncludeNoDateChecked="checked";
        
        public $searchLevels = [];
        public $searchLevelsID = [];
        public $searchIncludeNoLevels=1;
        public $searchIncludeNoLevelsChecked="checked";

        public $searchTopics = [];
        public $searchTopicsSolution = [];
        public $searchTopicOperator = "Or";
        public $searchSolutionOperator = "Or";
        public $searchBothOperator = "Or";

        public $page = 0;
        public $pageStart = 1;
        public $pageEnd = 10;


        function findRefsID(){
            //IF $refs is not set, get it. NOT IMPLEMENTED YET.
            foreach( $this->refs as $r ){
                if (in_array($r->ref, $this->searchRefs ) ){
                    array_push($this -> searchRefsID, $r->ID);
                }
            }

        }


        function getRefsUsed( ){
            //Get the references from the database;
            $this -> refs =  $this -> conn -> getRefsUsed();
        }


        function printHtmlRef(){
            $ret = "";
            foreach( $this->refs as $r){
                if (in_array( $r->ref, $this->searchRefs )){
                    $ret.=  '<option selected value="' . $r->ref . '">' .$r->ref.'</option>';
                }else{
                    $ret.='<option value="' . $r->ref . '">' .$r->ref.'</option>';
                }
            }
            return $ret;
        }
        function printRefs(){
            //print_r( $this->refs );
        }

        function setDB( $conn ){
            $this -> conn = $conn;
        }
        function setSearchRefs( $arr ){
            if (isset( $arr['r'] ) ){
                foreach( $arr['r'] as $q ){
                  array_push($this -> searchRefs, $q);
                }
            }
            $this -> findRefsID();  
        }
        function setSearchYearQ($arr){

            if (isset( $arr['yearFromQ'] ) ){
                $this -> searchYearFromQ = $arr['yearFromQ'];
            }
            if (isset( $arr['yearToQ'] ) ){
                $this-> searchYearToQ = $arr['yearToQ'];
            }
            if (isset( $arr['includeNoDate'] ) ){
                $this->searchIncludeNoDate = 1;
                $this->searchIncludeNoDateChecked=" checked "; //Not checked
            }else{
                $this->searchIncludeNoDate = 0;
                $this->searchIncludeNoDateChecked=""; //Not checked
                    
            }
            
        }

        function setPage($arr){
            if (isset( $arr['page'] ) ){
                $this -> page = $arr['page'];
            }

            $this -> pageStart = 1 + 10*($this-> page) ;
            $this -> pageEnd = $this->pageStart + 9;

            //print_r( $this );

        }


        function findIdInLevel($arr, $q){
            foreach( $arr as $a){
                if ( strcmp( $a->level, $q )=== 0 ){
                    return $a->ID;
                }
            }
        }

        function setSearchLevels($arr, $levels){
            if (isset( $arr['l'] ) ){
                foreach( $arr['l'] as $q ){
                    array_push($this->searchLevels, $q);
                    //Get the ID of the level:
                    array_push($this->searchLevelsID, $this -> findIdInLevel( $levels, $q) );
                }
            }
            //print_r( $this->searchLevelsID );
            if (!isset( $arr['includeNoLevels'] ) ){
                $this -> searchIncludeNoLevels = 0;
                $this -> searchIncludeNoLevelsChecked="";
              }
        }
        function setSearchAdvanced($arr){
            if (isset( $arr['advanced'] ) ){
                $this -> searchAdvanced = $arr['advanced'];
                $this -> searchAdvancedchecked = "checked";
            }
        }

        function setSearchTopics($arr){
            if (isset( $arr['q'] ) ){
                foreach( $arr['q'] as $q ){
                    array_push($this -> searchTopics, $q);
                }
            }
            if (isset( $arr['boolean_topic'] ) ){
                $this->searchTopicOperator = $arr['boolean_topic'];
            }            
            if (isset( $arr['s'] ) ){
                foreach( $arr['s'] as $q ){
                    array_push($this->searchTopicsSolution, $q);
                }
            }
            if (isset( $arr['boolean_solution'] ) ){
                $this->searchSolutionOperator = $arr['boolean_solution'];
            }
            if (isset( $arr['boolean_both'] ) ){
                $this->searchBothOperator = $arr['boolean_both'];
            }
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
    class images{}
	
	
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
