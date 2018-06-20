<?php
	date_default_timezone_set('Asia/Hong_Kong');

	$connFile=isset($_REQUEST["conn"])?$_REQUEST["conn"]:"";
	$tableFile=isset($_REQUEST["table"])?$_REQUEST["table"]:"";
	
	if(strlen($connFile)>0){
		$argv=array();
		$argv[]="script_gendao.php";
		$argv[]=$connFile;
		$argv[]=$tableFile;
	}
	
	if (count($argv) < 2) { print "usage: script_gendao.php [input-file-name] [input-file-name]\n"; die(); }
	
	include_once($argv[1]);
	$paramArray=array("connectionMethod", "dbUser", "dbPassword", "dbHost", "dbPort", "dbInstanceName", "filePrefix", "loggerPath", "defaultRootPath", "saveHistory");
	$conn_pass=true;
	foreach($paramArray as $paramItem){ if(!isset($connectionArray[$paramItem])){ print("Missing parameter: ".$paramItem."\n"); $conn_pass=false;} }
	
	if($conn_pass==false){ die; }
	
	/****** Check folder exist *********/
	$defaultRootPath=$connectionArray["defaultRootPath"];
	$defaultDaoPath=$defaultRootPath."/"."dao";
	$defaultDaoClassPath=$defaultDaoPath."/".(strlen($connectionArray["filePrefix"])>0?$connectionArray["filePrefix"]."/":"")."class";
	$defaultDaoTablePath=$defaultDaoPath."/".(strlen($connectionArray["filePrefix"])>0?$connectionArray["filePrefix"]."/":"")."table";
	$defaultDaoReadmePath=$defaultDaoPath."/".(strlen($connectionArray["filePrefix"])>0?$connectionArray["filePrefix"]."/":"")."readme";
	$defaultTemplatePath=dirname(__FILE__)."/template";
	$defaultLoggerPath=$connectionArray["loggerPath"];
	if(!file_exists($defaultDaoPath)){ mkdir($defaultDaoPath, 0775, true); }
	if(!file_exists($defaultDaoClassPath)){ mkdir($defaultDaoClassPath, 0775, true); }
	if(!file_exists($defaultDaoTablePath)){ mkdir($defaultDaoTablePath, 0775, true); }
	if(!file_exists($defaultDaoReadmePath)){ mkdir($defaultDaoReadmePath, 0775, true); }
	if(!file_exists($defaultLoggerPath)){ mkdir($defaultLoggerPath, 0775, true); }
	/****** End- Check folder exist *********/

	$createDatetime=date("YmdHis");
if(1==2){	
	/**** Create log4php.xml File ******/
	print("Create log4php "." --"."\n");
	$createFileName = "log4php";
	$createFileFullPathName=$defaultRootPath."/".$createFileName.".xml";
	$readHandle = fopen($defaultTemplatePath."/template_".$createFileName.".txt", 'r') or die('Cannot open file:  '.$createFileName.".txt");
	$readContent=fread($readHandle, filesize($defaultTemplatePath."/template_".$createFileName.".txt"));
	$readContent=
		str_replace(
			array("[%loggerPath%]"),
			array($connectionArray["loggerPath"]),
			$readContent);
	fclose($readHandle);
	$writeHandle = fopen($createFileFullPathName.".".$createDatetime, 'w') or die('Cannot open file:  '.$createFileFullPathName.".".$createDatetime);
	fwrite($writeHandle, $readContent);
	fclose($writeHandle);
	$result=compareAndCopy($createFileFullPathName.".".$createDatetime, $createFileFullPathName);
	print("	| Done!"."\n");
	/**** End - Create log4php.xml File ******/
	
	/**** Create GenericDO.php File ******/
	print("Create GenericDO "." --"."\n");
	$createFileName = "GenericDO";
	$createFileFullPathName=$defaultDaoPath."/".$createFileName.".php";
	$readHandle = fopen($defaultTemplatePath."/template_".$createFileName.".txt", 'r') or die('Cannot open file:  '.$createFileName.".txt");
	$readContent=fread($readHandle, filesize($defaultTemplatePath."/template_".$createFileName.".txt"));
	fclose($readHandle);
		
	$writeHandle = fopen($createFileFullPathName.".".$createDatetime, 'w') or die('Cannot open file:  '.$createFileFullPathName.".".$createDatetime);
	fwrite($writeHandle, $readContent);
	fclose($writeHandle);
	$result=compareAndCopy($createFileFullPathName.".".$createDatetime, $createFileFullPathName);
	print("	| Done!"."\n");
	/**** End - Create GenericDO.php File ******/
	
	/**** Create ConnectionPool.php File ******/
	print("Create ConnectionPool "." --"."\n");
	$createFileName = "ConnectionPool";
	$createFileFullPathName=$defaultDaoPath."/".$connectionArray["filePrefix"].$createFileName.".php";
	$readHandle = fopen($defaultTemplatePath."/template_".$createFileName.".txt", 'r') or die('Cannot open file:  '.$createFileName.".txt");
	$readContent=fread($readHandle, filesize($defaultTemplatePath."/template_".$createFileName.".txt"));
	$readContent=
		str_replace(
			array("[%filePrefix%]", "[%dbUser%]", "[%dbPassword%]", "[%connectionMethod%]", "[%dbHost%]", "[%dbPort%]", "[%dbInstanceName%]"),
			array($connectionArray["filePrefix"], $connectionArray["dbUser"], $connectionArray["dbPassword"], $connectionArray["connectionMethod"], $connectionArray["dbHost"], $connectionArray["dbPort"], $connectionArray["dbInstanceName"], ),
			$readContent);
	fclose($readHandle);
		
	$writeHandle = fopen($createFileFullPathName.".".$createDatetime, 'w') or die('Cannot open file:  '.$createFileFullPathName.".".$createDatetime);
	fwrite($writeHandle, $readContent);
	fclose($writeHandle);
	$result=compareAndCopy($createFileFullPathName.".".$createDatetime, $createFileFullPathName);
	print("	| Done!"."\n");
	/**** End -Create ConnectionPool.php File ******/
	
	/**** Create DO.php File ******/
	print("Create DAO "." --"."\n");
	$createFileName = "DAO";
	$createFileFullPathName=$defaultDaoPath."/".$connectionArray["filePrefix"].$createFileName.".php";
	$readHandle = fopen($defaultTemplatePath."/template_".$createFileName.".txt", 'r') or die('Cannot open file:  '.$createFileName.".txt");
	$readContent=fread($readHandle, filesize($defaultTemplatePath."/template_".$createFileName.".txt"));
	$readContent=
		str_replace(
			array("[%filePrefix%]"),
			array($connectionArray["filePrefix"]),
			$readContent);
	fclose($readHandle);
		
	$writeHandle = fopen($createFileFullPathName.".".$createDatetime, 'w') or die('Cannot open file:  '.$createFileFullPathName.".".$createDatetime);
	fwrite($writeHandle, $readContent);
	fclose($writeHandle);
	$result=compareAndCopy($createFileFullPathName.".".$createDatetime, $createFileFullPathName);
	print("	| Done!"."\n");
	/**** End -Create DO.php File ******/
}	
	
	include_once(dirname(__FILE__) . "/../../lib/adodb/adodb.inc.php");
	$conn=getConnection($connectionArray["connectionMethod"], $connectionArray["dbHost"], $connectionArray["dbUser"], $connectionArray["dbPassword"], $connectionArray["dbInstanceName"]);
	
	if($connectionArray["connectionMethod"]=="oci8"){
		$tableArray=showOracleTables($conn);
	}else{
		$tableArray=showTables($conn);
	}	
	
	if(isset($argv[2])){
		if(strlen($argv[2])>0){
			include_once($argv[2]);
		}
	}

	foreach($tableArray as $tableItem){
		if(isset($todoTableArray)){
			if(sizeof($todoTableArray)>0){ if(!in_array($tableItem, $todoTableArray)){ print $tableItem." skip creation"."\n"; continue; } }
			if(sizeof($skipTableArray)>0){ if(in_array($tableItem, $skipTableArray)){ print $tableItem." skip creation"."\n"; continue; } }
		}
		if($connectionArray["connectionMethod"]=="oci8"){
			$columnArray=showOracleColumns($conn, $tableItem);
		}else{
			$columnArray=showColumns($conn, $tableItem);
		}

		print("Create data object: ".$tableItem." --"."\n");

		$writeHandle = fopen($defaultDaoTablePath."/".genPara($tableItem).".php" .".".$createDatetime, 'w') or die('Cannot open file:  '.$defaultDaoTablePath."/".genPara($tableItem).".php" .".".$createDatetime);
		fwrite($writeHandle, '<?php'."\n");
		fwrite($writeHandle, 'include_once(dirname(__FILE__)."/../../'.(strlen($connectionArray["filePrefix"])>0?"../":"").'dao/'.$connectionArray["filePrefix"].'DAO.php");'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, 'class '.genPara($tableItem).' extends GenericDO{'."\n");
		fwrite($writeHandle, '	protected $dbcon = null;'."\n");
		fwrite($writeHandle, '	protected static $logger = null;'."\n");
		fwrite($writeHandle, ''."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '	protected $'.genPara($columnItem->name).'=null;'."\n");
		}
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	public static function staticInit(){'."\n");
		fwrite($writeHandle, '		Logger::configure(dirname(__FILE__) . "/../../'.(strlen($connectionArray["filePrefix"])>0?"../":"").'log4php.xml");'."\n");
		fwrite($writeHandle, '		self::$logger = Logger::getLogger(__FILE__);'."\n");
		fwrite($writeHandle, '	}'."\n");
		
		fwrite($writeHandle, '	public function '.genPara($tableItem).'(){}'."\n");
		fwrite($writeHandle, ''."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '	public function get_'.genPara($columnItem->name).'($unhex=false){ return ($unhex==false?$this->'.genPara($columnItem->name).':(GenericDO::hexToStr($this->'.genPara($columnItem->name).'))); }'."\n");
		}
		fwrite($writeHandle, ''."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '	public function set_'.genPara($columnItem->name).'($value, $unhex=false){ $this->'.genPara($columnItem->name).'=($unhex==false?(is_numeric($value)?$value:trim($value)):(GenericDO::hexToStr(is_numeric($value)?$value:trim($value)))); }'."\n");
		}
		fwrite($writeHandle, ''."\n");
		
		/**** Readme text ****/
		$readmeWriteHandle = fopen($defaultDaoReadmePath."/".genPara($tableItem).".php.".$createDatetime, 'w') or die('Cannot open file:  '.$defaultDaoReadmePath."/".genPara($tableItem).".php.".$createDatetime);
		
		fwrite($readmeWriteHandle, '<pre>'."\n");
		fwrite($readmeWriteHandle, '<?php'."\n");
		fwrite($readmeWriteHandle, '	date_default_timezone_set("Asia/Hong_Kong");'."\n");
		fwrite($readmeWriteHandle, '	include_once(dirname(__FILE__)."/dao/'.$connectionArray["filePrefix"].'DAO.php");'."\n");
		fwrite($readmeWriteHandle, '	$'.$connectionArray["filePrefix"].'Con='.$connectionArray["filePrefix"].'DAO::acquireConnectionPool()->getConnection(); '."\n");
		fwrite($readmeWriteHandle, '	if ($'.$connectionArray["filePrefix"].'Con != null){'.$connectionArray["filePrefix"].'DAO::acquireConnectionPool()->returnConnection($'.$connectionArray["filePrefix"].'Con);}'."\n");
		fwrite($readmeWriteHandle, '?>'."\n");
		fwrite($readmeWriteHandle, ''."\n");
	
		fwrite($readmeWriteHandle, 'include_once(dirname(__FILE__)."/dao/'.(strlen($connectionArray["filePrefix"])>0?$connectionArray["filePrefix"]."/":"").'class/'.genPara("class_".$tableItem).'.php");'."\n");
		fwrite($readmeWriteHandle, '$'.genPara("obj_".$tableItem).'=new '.genPara("class_".$tableItem).'();'."\n");
		fwrite($readmeWriteHandle, '$'.genPara("obj_".$tableItem).'->resetObject();'."\n");
		foreach($columnArray as $columnItem){
			fwrite($readmeWriteHandle, '$'.genPara("obj_".$tableItem).'->set_'.genPara($columnItem->name).'();'."\n");
		}
		fwrite($readmeWriteHandle, '//$'.genPara("obj_".$tableItem).'->loadFromDB($'.$connectionArray["filePrefix"].'Con);'."\n");
		fwrite($readmeWriteHandle, '//$'.genPara("obj_".$tableItem).'->saveToDB($'.$connectionArray["filePrefix"].'Con);'."\n");
		fwrite($readmeWriteHandle, ''."\n");

		fwrite($readmeWriteHandle, 'include_once(dirname(__FILE__)."/dao/'.(strlen($connectionArray["filePrefix"])>0?$connectionArray["filePrefix"]."/":"").'class/'.genPara("class_".$tableItem).'.php");'."\n");
		fwrite($readmeWriteHandle, '$'.genPara("obj_".$tableItem).'=new '.genPara("class_".$tableItem).'();'."\n");
		fwrite($readmeWriteHandle, '$'.genPara("obj_".$tableItem).'->resetObject();'."\n");
		foreach($columnArray as $columnItem){
			fwrite($readmeWriteHandle, '$'.genPara("obj_".$tableItem).'->get_'.genPara($columnItem->name).'();'."\n");
		}
		fwrite($readmeWriteHandle, '//$'.genPara("obj_".$tableItem).'->loadFromDB($'.$connectionArray["filePrefix"].'Con);'."\n");
		//fwrite($readmeWriteHandle, '//$'.genPara("obj_".$tableItem).'->saveToDB($'.$connectionArray["filePrefix"].'Con);'."\n");
		fwrite($readmeWriteHandle, '</pre>'."\n");
		fwrite($readmeWriteHandle, ''."\n");
		
		fclose($readmeWriteHandle);
		
		/**** End-Readme text ****/
		
		fwrite($writeHandle, '	public function loadFromDBWithConnection($con){'."\n");
		fwrite($writeHandle, '		$sqlSel = "SELECT '."\n");
		$colCnt=0;
		foreach($columnArray as $columnItem){
			$colCnt++;
			fwrite($writeHandle, "				".$columnItem->name.($colCnt>=sizeof($columnArray)?"":",")."\n");
		}
		fwrite($writeHandle, '			FROM '.$tableItem.' WHERE ";'."\n");
		fwrite($writeHandle, '		$sqlWhereClause = "";'."\n");
		fwrite($writeHandle, '		$params = array();'."\n");
		foreach($columnArray as $columnItem){
			//if($columnItem->type=="datetime"){ fwrite($writeHandle, '// '); }
			fwrite($writeHandle, '		if($this->'.genPara($columnItem->name).'!=null){ $sqlWhereClause.="AND '.$columnItem->name.'=? "; $params[]=$this->'.genPara($columnItem->name).'; }'."\n");
		}
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '		if(strlen($sqlWhereClause) > 0){ $sqlWhereClause = substr($sqlWhereClause, (strlen("AND "))); }'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '		try{'."\n");
		fwrite($writeHandle, '			if ($con == null){throw new Exception("Can\'t get connection");}'."\n");
		fwrite($writeHandle, '			$rs = $con->Execute($sqlSel . $sqlWhereClause, $params);'."\n");
		fwrite($writeHandle, '			if($rs != null){'."\n");
		fwrite($writeHandle, '				while (!$rs->EOF){'."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '					$this->'.genPara($columnItem->name).'=$rs->fields["'.$columnItem->name.'"];'."\n");
		}
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '					$this->loaded = true;'."\n");
		fwrite($writeHandle, '					$this->newRecord = false;'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '					$rs->MoveNext();'."\n");
		fwrite($writeHandle, '				}'."\n");
		fwrite($writeHandle, '			}'."\n");
		fwrite($writeHandle, '		}catch (Exception $e){throw e;}'."\n");
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	protected function insertToDBWithConnection($con){'."\n");
		fwrite($writeHandle, '		$sqlIns =  "INSERT INTO '.$tableItem.' ('."\n");
		$colCnt=0;
		foreach($columnArray as $columnItem){
			$colCnt++;
			fwrite($writeHandle, "				".$columnItem->name.($colCnt>=sizeof($columnArray)?"":",")."\n");
		}
		fwrite($writeHandle, '			) VALUES ('."\n");
		$colCnt=0;
		foreach($columnArray as $columnItem){
			$colCnt++;
			fwrite($writeHandle, "				"."?".($colCnt>=sizeof($columnArray)?"":",")."\n");
		}
		fwrite($writeHandle, '			)";'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '		$paramsArray = array('."\n");
		$colCnt=0;
		foreach($columnArray as $columnItem){
			$colCnt++;
			fwrite($writeHandle, "		".'$this->'.genPara($columnItem->name).($colCnt>=sizeof($columnArray)?"":",")."\n");
		}
		fwrite($writeHandle, '		);'."\n");
		fwrite($writeHandle, '		try{'."\n");
		fwrite($writeHandle, '			if ($con == null){throw new Exception("Can\'t get connection");}'."\n");
		fwrite($writeHandle, '			try{'."\n");
		fwrite($writeHandle, '				$result = $con->Execute($sqlIns, $paramsArray);'."\n");
		fwrite($writeHandle, ' 				$rs = $con->Execute("SELECT LAST_INSERT_ID() as new_index");'."\n");
		foreach($columnArray as $columnItem){
			if($columnItem->primary_key){
				fwrite($writeHandle, ' 				if ($rs != null){$this->'.genPara($columnItem->name).'=$rs->fields["new_index"];}'."\n");
				break;
			}
		}
		fwrite($writeHandle, '			}catch (Exception $e){throw e;}'."\n");
		fwrite($writeHandle, ''."\n");
		if($tableItem!="history_log" && $connectionArray["saveHistory"]==1){
			fwrite($writeHandle, '			$this->saveHistory($con, "insertToDBWithConnection");'."\n");
		}
		fwrite($writeHandle, '			$this->loaded = true;'."\n");
		fwrite($writeHandle, '			$this->newRecord = false;'."\n");
		fwrite($writeHandle, '			$this->changed = false;'."\n");
		fwrite($writeHandle, '		}catch (Exception $e){throw e;}'."\n");
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
		
		$priKey=false;
		foreach($columnArray as $columnItem){ if($columnItem->primary_key){ $priKey=true; break;} }
		if($priKey){
			fwrite($writeHandle, '	protected function updateToDBWithConnection($con){'."\n");
			fwrite($writeHandle, '		$sqlUpd="UPDATE '.$tableItem.' SET '."\n");
			$colCnt=0;
			foreach($columnArray as $columnItem){
				$colCnt++;
				if(!$columnItem->primary_key){
					fwrite($writeHandle, "				".$columnItem->name.'=?'.($colCnt>=sizeof($columnArray)?"":",")."\n");
				}
			}
			fwrite($writeHandle, '			WHERE '."\n");
			$colCnt=0;
			foreach($columnArray as $columnItem){
				if($columnItem->primary_key){
					fwrite($writeHandle, '			'.($colCnt<>0?'AND ':'').$columnItem->name.'=?'."\n");
					$colCnt++;
				}
			}
			fwrite($writeHandle, '		";'."\n");
			fwrite($writeHandle, ''."\n");
			fwrite($writeHandle, '		try{'."\n");
			fwrite($writeHandle, '			$paramsArray = array('."\n");
			foreach($columnArray as $columnItem){
				if(!$columnItem->primary_key){
					fwrite($writeHandle, '				$this->'.genPara($columnItem->name).","."\n");
				}
			}
			fwrite($writeHandle, '				//key'."\n");
			foreach($columnArray as $columnItem){
				if($columnItem->primary_key){
					fwrite($writeHandle, '				$this->'.genPara($columnItem->name).","."\n");
				}
			}
			fwrite($writeHandle, '			);'."\n");
			fwrite($writeHandle, '			try{'."\n");
			fwrite($writeHandle, '				$con->Execute($sqlUpd, $paramsArray);'."\n");
			fwrite($writeHandle, '			}catch (Exception $e){throw e;}'."\n");
			fwrite($writeHandle, ''."\n");
			if($tableItem!="history_log" && $connectionArray["saveHistory"]==1){
				fwrite($writeHandle, '			$this->saveHistory($con, "updateToDBWithConnection");'."\n");
			}
			fwrite($writeHandle, '			$this->loaded = true;'."\n");
			fwrite($writeHandle, '			$this->newRecord = false;'."\n");
			fwrite($writeHandle, '			$this->changed = false;'."\n");
			fwrite($writeHandle, '		}catch (Exception $e){throw e;}'."\n");
			fwrite($writeHandle, '	}'."\n");
			fwrite($writeHandle, ''."\n");
			
			fwrite($writeHandle, '	public function deleteFromDBWithConnection($con){'."\n");
			fwrite($writeHandle, '		if('."\n");
			$colCnt=0;
			foreach($columnArray as $columnItem){
				if($columnItem->primary_key){
					if($colCnt<>0){ fwrite($writeHandle, '			&& '); }else{ fwrite($writeHandle, '			'); }
					fwrite($writeHandle, '$this->'.genPara($columnItem->name).'==null'."\n");
					$colCnt++;
				}
			}
			fwrite($writeHandle, '		){ return false; }'."\n");
			fwrite($writeHandle, ''."\n");
			fwrite($writeHandle, '		$sqlUpd = "DELETE FROM '.$tableItem.' WHERE '."\n");
			$colCnt=0;
			foreach($columnArray as $columnItem){
				if($columnItem->primary_key){
					if($colCnt<>0){ fwrite($writeHandle, '			AND '); }else{ fwrite($writeHandle, '			'); }
					fwrite($writeHandle, ''.$columnItem->name.' = ?'."\n");
					$colCnt++;
				}
			}
			fwrite($writeHandle, '		";'."\n");
			fwrite($writeHandle, ''."\n");
			fwrite($writeHandle, '		try{'."\n");
			fwrite($writeHandle, '			$paramsArray = array('."\n");
			foreach($columnArray as $columnItem){
				if($columnItem->primary_key){
					fwrite($writeHandle, '				$this->'.genPara($columnItem->name).','."\n");
				}
			}
			fwrite($writeHandle, '			);'."\n");
			fwrite($writeHandle, '			try{'."\n");
			fwrite($writeHandle, '				$con->Execute($sqlUpd, $paramsArray);'."\n");
			if($tableItem!="history_log" && $connectionArray["saveHistory"]==1){
				fwrite($writeHandle, '			$this->saveHistory($con, "deleteFromDBWithConnection");'."\n");
			}
			fwrite($writeHandle, '			}catch (Exception $e){throw e;}'."\n");
			fwrite($writeHandle, '		}catch (Exception $e){throw e;}'."\n");
			fwrite($writeHandle, '		return true;'."\n");
			fwrite($writeHandle, '	}'."\n");
			
		}else{
			fwrite($writeHandle, '	//No primary key found. No Update /Delete function added.'."\n");
			print("	| No primary key found. No Update / Delete function added."."\n");
		}
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	public function resetObject(){'."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '		$this->'.genPara($columnItem->name).'=null;'."\n");
		}
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '		$this->newRecord = false;'."\n");
		fwrite($writeHandle, '		parent::resetObject();'."\n");
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	public function toString(){'."\n");
		fwrite($writeHandle, '		return parent::toString() . "|'.genPara($tableItem).'.toString"'."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '			. "|\t" . "'.genPara($columnItem->name).'" . ": |" . $this->'.genPara($columnItem->name).''."\n");
		}
		fwrite($writeHandle, '		. "|";'."\n");
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	public function toJson(){'."\n");
		fwrite($writeHandle, '		return json_encode('."\n");
		fwrite($writeHandle, '			array('."\n");
		fwrite($writeHandle, '				"table"=>"'.$tableItem.'", '."\n");
		fwrite($writeHandle, '				"fields"=>array('."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '					"'.genPara($columnItem->name).'"=>$this->'.genPara($columnItem->name).', '."\n");
		}
		fwrite($writeHandle, '				),'."\n");
		fwrite($writeHandle, '			)'."\n");
		fwrite($writeHandle, '		);'."\n");
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
		
		if($connectionArray["saveHistory"]==1){
			saveHistoryBlock($connectionArray["filePrefix"], $writeHandle, $tableItem, $columnArray);
		}
		
		fwrite($writeHandle, '} '.genPara($tableItem).'::staticInit();'."\n");
		fwrite($writeHandle, '?>');
		fclose($writeHandle);
		$result=compareAndCopy($defaultDaoTablePath."/".genPara($tableItem).".php" .".".$createDatetime, $defaultDaoTablePath."/".genPara($tableItem).".php");
		print("	| Done!"."\n");
		
		print("Create class object: ".$tableItem." --"."\n");

		$writeHandle = fopen($defaultDaoClassPath."/".genPara("class_".$tableItem).".php" .".".$createDatetime, 'w') or die('Cannot open file:  '.$defaultDaoClassPath."/".genPara("class_".$tableItem).".php" .".".$createDatetime);
		fwrite($writeHandle, '<?php'."\n");
		fwrite($writeHandle, 'include_once(dirname(__FILE__)."/../../'.(strlen($connectionArray["filePrefix"])>0?"../":"").'dao/'.(strlen($connectionArray["filePrefix"])>0?$connectionArray["filePrefix"]."/":"").'table/'.genPara($tableItem).'.php");'."\n");
		fwrite($writeHandle, 'class '.genPara("class_".$tableItem).' extends '.genPara($tableItem).'{'."\n");
		fwrite($writeHandle, '	protected static $logger = null;'."\n");
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	public static function staticInit(){'."\n");
		fwrite($writeHandle, '		Logger::configure(dirname(__FILE__) . "/../../'.(strlen($connectionArray["filePrefix"])>0?"../":"").'log4php.xml");'."\n");
		fwrite($writeHandle, '		self::$logger = Logger::getLogger(__FILE__);'."\n");
		fwrite($writeHandle, '	}'."\n");
		
		fwrite($writeHandle, '	public function '.genPara("class_".$tableItem).'(){}'."\n");
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '	public static function customFind($sqlCondition, $paramsArray, $sortOrder, $conn, $returnRS=false){'."\n");
		fwrite($writeHandle, '		$resultArray = array();'."\n");
		fwrite($writeHandle, '		$sqlStmt = "SELECT'."\n");
		$colCnt=0;
		foreach($columnArray as $columnItem){
			$colCnt++;
			fwrite($writeHandle, "				".$columnItem->name.($colCnt>=sizeof($columnArray)?"":",")."\n");
		}
		fwrite($writeHandle, '			FROM '.$tableItem.' "'."\n");
		fwrite($writeHandle, '		. ($sqlCondition != null && strlen($sqlCondition) > 0 ? " WHERE " . $sqlCondition : "") '."\n");
		fwrite($writeHandle, '		. ($sortOrder != null && strlen($sortOrder) > 0 ? " ORDER BY " . $sortOrder : "");'."\n");
		fwrite($writeHandle, ''."\n");
		//fwrite($writeHandle, '		$conn = null;'."\n");
		fwrite($writeHandle, '		$rs = null;'."\n");
		fwrite($writeHandle, '		try{'."\n");
		fwrite($writeHandle, '			if($paramsArray != null && count($paramsArray) > 0){ $rs = $conn->Execute($sqlStmt, $paramsArray); }else{ $rs = $conn->Execute($sqlStmt); }'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '			if($rs != null){'."\n");
		fwrite($writeHandle, '				if($returnRS){'."\n");
		fwrite($writeHandle, '					$resultArray=$rs;'."\n");
		fwrite($writeHandle, '				}else{'."\n");
		fwrite($writeHandle, '					while (!$rs->EOF){'."\n");
		fwrite($writeHandle, '						$currDetail=new '.genPara("class_".$tableItem).'();'."\n");
		fwrite($writeHandle, ''."\n");
		foreach($columnArray as $columnItem){
			fwrite($writeHandle, '						$currDetail->'.genPara($columnItem->name).'=$rs->fields["'.$columnItem->name.'"];'."\n");
		}
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '						$currDetail->loaded = true;'."\n");
		fwrite($writeHandle, '						$currDetail->newRecord = false;'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '						$resultArray[] = $currDetail;'."\n");
		fwrite($writeHandle, '						$rs->MoveNext();'."\n");
		fwrite($writeHandle, '					}'."\n");
		fwrite($writeHandle, '				}'."\n");
		fwrite($writeHandle, '			}'."\n");
		fwrite($writeHandle, '		}catch (Exception $e){throw e;}'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '		return $resultArray;'."\n");
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
		
		fwrite($writeHandle, '//[%CUSTOM_AREA_START%]--DONT_CHANGE_OR_REMOVE'."\n");
		if(file_exists($defaultDaoClassPath."/".genPara("class_".$tableItem).".php")){ 
			$capStart=false;
			$readHandle = fopen($defaultDaoClassPath."/".genPara("class_".$tableItem).".php", 'r') or die('Cannot open file:  '.$defaultDaoClassPath."/".genPara("class_".$tableItem).".php");
			while (!feof($readHandle)) {
				$value = fgets($readHandle);
				if($value=="//[%CUSTOM_AREA_END%]--DONT_CHANGE_OR_REMOVE"."\n"){ $capStart=false; }
				if($capStart){ fwrite($writeHandle, $value); }
				if($value=="//[%CUSTOM_AREA_START%]--DONT_CHANGE_OR_REMOVE"."\n"){ $capStart=true; }
			}
			fclose($readHandle);
		}else{
			fwrite($writeHandle, ''."\n");
		}
		fwrite($writeHandle, '//[%CUSTOM_AREA_END%]--DONT_CHANGE_OR_REMOVE'."\n");
		fwrite($writeHandle, ''."\n");
		fwrite($writeHandle, '} '.genPara("class_".$tableItem).'::staticInit();'."\n");
		fwrite($writeHandle, '?>'."\n");
		$result=compareAndCopy($defaultDaoClassPath."/".genPara("class_".$tableItem).".php" .".".$createDatetime, $defaultDaoClassPath."/".genPara("class_".$tableItem).".php");
		print("	| Done!"."\n");
		
		//break;
	}
	
	returnConnection($conn);
	
	
	
	
	
	
	
	
	
	
	function getConnection($connectionMethod, $dbHost, $dbUser, $dbPassword, $dbInstanceName)
	{
		$conn=null;
		try{
			$conn = NewADOConnection($connectionMethod);
			$conn->Connect($dbHost, $dbUser, $dbPassword, $dbInstanceName);
		}catch (Exception $e){ 
			$conn=null;
			throw new Exception($e->getMessage());
		}
		
		if($conn!=null){ $conn->Execute("set names 'utf8'"); }
		return $conn;
	}
	function returnConnection($conn) { if ($conn != null){ $conn->close(); } }
	function showDatabases($conn){ return $conn->MetaDatabases(); }
	function showTables($conn){ return $conn->MetaTables(); }
	function showOracleTables($conn){
		$tableArray=array();
		$tmpRS=$conn->execute("select distinct OWNER, TABLE_NAME FROM ALL_TABLES");
		while (!$tmpRS->EOF){
			$tableArray[]=$tmpRS->fields["OWNER"].".".$tmpRS->fields["TABLE_NAME"];
			$tmpRS->MoveNext();
		}
		
		$tmpRS=$conn->execute("select distinct OWNER, VIEW_NAME FROM ALL_VIEWS");
		while (!$tmpRS->EOF){
			$tableArray[]=$tmpRS->fields["OWNER"].".".$tmpRS->fields["VIEW_NAME"];
			$tmpRS->MoveNext();
		}
		return $tableArray;
	}
	function showColumns($conn, $tableName){ return $conn->MetaColumns($tableName); }
	function showOracleColumns($conn, $tableName){
		$tableStructure=explode(".", $tableName);
		$owner="%";
		if(sizeof($tableStructure)>1){
			$owner=$tableStructure[0];
			$tableName=$tableStructure[1];
		}
		$tmpCol=$conn->execute("select COLUMN_NAME from ALL_TAB_COLUMNS where TABLE_NAME = '".$tableName."' AND OWNER LIKE '".$owner."' ORDER BY COLUMN_ID");
		$columnArray=array();
		while (!$tmpCol->EOF){
			$columnArray[]=array("name"=>$tmpCol->fields["COLUMN_NAME"], "primary_key"=>checkOraclePrimaryKey($conn, $tableName, $tmpCol->fields["COLUMN_NAME"]), "type"=>"");
			$tmpCol->MoveNext();
		}
		$columnArray=json_encode($columnArray);
		return json_decode($columnArray);
	}
	function checkOraclePrimaryKey($conn, $tableName, $colName){
		$tableStructure=explode(".", $tableName);
		$owner="%";
		if(sizeof($tableStructure)>1){
			$owner=$tableStructure[0];
			$tableName=$tableStructure[1];
		}
		$rs=$conn->execute("SELECT cols.table_name, cols.column_name, cols.position, cons.status, cons.owner FROM all_constraints cons, all_cons_columns cols WHERE cols.table_name = '".$tableName."' AND cols.column_name='".$colName."' AND cons.constraint_type = 'P' AND cons.constraint_name = cols.constraint_name AND cons.owner = cols.owner AND cons.owner LIKE '".$owner."' ORDER BY cols.table_name, cols.position");
		$pkey=false;
		while(!$rs->EOF){
			$pkey=true;
			$rs->MoveNext();
		}
		return $pkey;
	}
	function genPara($paras){
		$para_array=str_split(strtolower($paras)); $new_para=""; $capletter=0;
		foreach($para_array as $para){ if($para!="_" && $para!="."){ $new_para.=($capletter==1?strtoupper($para):$para); $capletter=0; }else{ $capletter=1; } }
		if(is_numeric(substr($new_para,0,1))){ $new_para="a".$new_para; }
		return $new_para;
	}
	function chklastfield($arrayField, $fieldValue){
		$tmpArray=array_keys($arrayField, $fieldValue);
		if(sizeof($arrayField)-1==$tmpArray[0]){return true;}else{return false;}
	}
	function compareAndCopy($newFilename, $oldFilename){
		if(file_exists($oldFilename)){ 
			print "	| Difference: ";
			$callOutput = system("diff " . $newFilename . " " . $oldFilename . " | wc -l ");
			//$callOutput = system("diff " . $newFilename . " " . $oldFilename . "  ");
			if ($callOutput > 0){
				rename($oldFilename, $oldFilename.".".date("YmdHis", filemtime($oldFilename))); 
				rename($newFilename, $oldFilename); 
				print "	| Replace File: ".$oldFilename."\n";
			}else{
				$callOutput = system("rm -f " . $newFilename . " ");
				print "	| Remain Current File "."\n";
			}
		}else{
			rename($newFilename, $oldFilename);
			print "	| Create New File "."\n";
		}
		return true;
	}
	function saveHistoryBlock($prefix, $writeHandle, $tableItem, $columnArray){
		fwrite($writeHandle, '	public function saveHistory($con, $action=""){'."\n");
		fwrite($writeHandle, '		include_once(dirname(__FILE__)."/../../../dao/'.$prefix.'/class/classHistoryLog.php");'."\n");
		fwrite($writeHandle, '		$objHistoryLog=new classHistoryLog();'."\n");
		fwrite($writeHandle, '		$objHistoryLog->resetObject();'."\n");
		fwrite($writeHandle, '		$objHistoryLog->set_logDatetime(date("Y-m-d H:i:s"));'."\n");
		fwrite($writeHandle, '		$objHistoryLog->set_logTableName("'.$tableItem.'");'."\n");
		foreach($columnArray as $columnItem){
			$keyField=genPara($columnItem->name);
			if($columnItem->primary_key){ $keyField=genPara($columnItem->name); break; }
		}
		fwrite($writeHandle, '		$objHistoryLog->set_refId($this->'.$keyField.');'."\n");
		fwrite($writeHandle, '		$objHistoryLog->set_logDetail($action." | ".$this->toJson());'."\n");
		fwrite($writeHandle, '		$objHistoryLog->saveToDB($con);'."\n");
		
		fwrite($writeHandle, '		require_once(dirname(__FILE__) . "/../../../lib/log4php/Logger.php");'."\n");
		fwrite($writeHandle, '		Logger::configure(dirname(__FILE__) . "/../../../log4php.xml");'."\n");
		fwrite($writeHandle, '		$logger = Logger::getLogger("HistoryLog");'."\n");
		fwrite($writeHandle, '		$logger->info("['.$tableItem.'] | ".$action." | ".$this->toJson());'."\n");
			
		fwrite($writeHandle, '	}'."\n");
		fwrite($writeHandle, ''."\n");
	}
	
	
?>