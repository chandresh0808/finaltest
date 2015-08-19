@echo off & setlocal ENABLEDELAYEDEXPANSION
C:\AuditCompanionScripts\auditcompanion-portal\curl-7.21.7\curl.exe -L http://169.254.169.254/latest/user-data/ > C:\AuditCompanionScripts\auditcompanion-portal\userdata

set file=C:\AuditCompanionScripts\auditcompanion-portal\userdata
::
for /f "tokens=1,* delims=:," %%a in (%file%) do (
      if not "%%b"=="" call :dequote %%b
      (for /f "tokens=* delims= " %%e in (%%a) do set atoken=%%e) 2>NUL
      if "!atoken!"=="req_id" set req_id=!btoken!
  if "!atoken!"=="db_host" set DB_HOST=!btoken!
  if "!atoken!"=="db_user" set DB_USER=!btoken!
  if "!atoken!"=="db_password" set DB_PWD=!btoken!
  if "!atoken!"=="db_database" set DB_NAME=!btoken!
  if "!atoken!"=="aws_region" set aws_region=!btoken!
  if "!atoken!"=="aws_access_key" set aws_access_key=!btoken!
  if "!atoken!"=="aws_secret_access_key" set aws_secret_access_key=!btoken!
  if "!atoken!"=="aws_bucket_name" set aws_bucket_name=!btoken!
      )
goto end
::
:dequote
set btoken=%~1
GOTO:EOF
::
:end


:StartProcessing 

echo
echo ========== Processing started please wait a movement...... ==========
echo
SET DB_PORT=3306
set createdir=C:\AuditCompanionScripts\auditcompanion-portal\DBSS\Worked\Extracted\ExtractedNew
set deletedir=C:\AuditCompanionScripts\auditcompanion-portal\DBSS
set deletefolder=C:\AuditCompanionScripts\auditcompanion-portal
set zipfile=C:\AuditCompanionScripts\auditcompanion-portal\DBSS\
set workdir=C:\AuditCompanionScripts\auditcompanion-portal\DBSS\Worked\
set extracteddir=C:\AuditCompanionScripts\auditcompanion-portal\DBSS\Worked\Extracted\
set extractednewdir=C:\AuditCompanionScripts\auditcompanion-portal\DBSS\Worked\Extracted\ExtractedNew\
set mainpath=C:\AuditCompanionScripts\auditcompanion-portal\
set auth=-S AMAZONA-OTBCKDN -U DataMatrixAdmin -P DataMatrix#1%%1 -d Datamatrix_sapdb
set copyauth=-S AMAZONA-OTBCKDN -U DataMatrixAdmin -P DataMatrix#1%%1
set rss_std=C:\AuditCompanionScripts\auditcompanion-portal\RSS_STD\
set reports=C:\AuditCompanionScripts\auditcompanion-portal\Reports\
set bcpcommand="C:\Program Files\Microsoft SQL Server\110\Tools\Binn\bcp.exe"
set mysqlpath=--host=%DB_HOST% --port=%DB_PORT% --user=%DB_USER% --password=%DB_PWD% -o %DB_NAME%

		
if %req_id%=="" goto:ErrorQuit

:CheckMySQLAgain

echo
:: Checking the Status for Analysis Request Table Starts here
echo ========== Checking the Status for Analysis Request Table ==========
echo
echo SELECT status FROM auditcompanion.analysis_request where id =%req_id%; > %mainpath%mysql.sql
CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
if NOT %ERRORLEVEL% == 0 goto:CheckMySQLAgain	
for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "status=%%x" )
set reportstatus1=Report Created
set reportstatus2=Completed
if %status%==%reportstatus1% goto:ErrorQuit
echo
if %status%==%reportstatus2% goto:ErrorQuit
echo
:: Checking the Status for Analysis Request Table Ends here


echo
:VerifyAnalysisRequest
:: Verify the Status for Analysis Request Table Starts here
echo ========== Verify the Status for Analysis Request Table ==========
echo
echo SELECT case source_spot_status when 'active' then 'active' else 'notactive' end as verify_spot_status FROM auditcompanion.analysis_request where id =%req_id%; > %mainpath%myverifysql.sql
CALL mysql %mysqlpath% < %mainpath%myverifysql.sql > %mainpath%myverifysql.txt
if NOT %ERRORLEVEL% == 0 goto:CheckMySQLAgain    
for /f "skip=1 tokens=* delims=" %%x in (%mainpath%myverifysql.txt)  do (set "status=%%x" )
set reportstatusActive=active
set reportstatusNotActive=notactive
echo status = %status%
if %status%==%reportstatusActive% goto:ContinueAnalysis
echo
if %status%==%reportstatusNotActive% goto:VerifyAnalysisRequest
echo 
goto:ErrorInProcessing
:: Checking the Status for Analysis Request Table Ends here


:ContinueAnalysis
echo
:: Setting Status for Analysis Request Table Starts here
echo ========== Setting Status for Analysis Request Table ==========
echo
echo UPDATE auditcompanion.analysis_request set status='Processed' where id =%req_id%; > %mainpath%mysql.sql
CALL mysql %mysqlpath% < %mainpath%mysql.sql
if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing	
echo
:: Setting Status for Analysis Request Table Ends here

:: Getting Extract Id from Analysis Request Table Starts here
echo ========== Getting Extract Id from Analysis Request Table ==========
echo
echo SELECT extract_id FROM auditcompanion.analysis_request where id =%req_id%; > %mainpath%mysql.sql
CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "extract_id=%%x" )
echo
:: Getting Extract Id from Analysis Request Table Ends here

:: Getting Job Id, System Salt Id and Extract file name from Extract Table Starts here
echo ========== Getting JobId, SystemSaltId and ExtractFileName from Extract Table ==========
echo
echo select job_id,system_salt_id,extract_file_name FROM auditcompanion.extracts where id=%extract_id%;> %mainpath%mysql.sql
CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
echo
  set Counter=0
  for /F "skip=1 tokens=1 " %%a in (%mainpath%mysql.txt) do (   
	 set job_id=%%a	
   )
   for /F "skip=1 tokens=2" %%a in (%mainpath%mysql.txt) do (   
	 set system_salt_id=%%a	
   )
   for /F "skip=1 tokens=3" %%a in (%mainpath%mysql.txt) do (   
	 set extract_file_name=%%a	
   )
   
  set initialDownloadFileName=DBSS_%job_id%
  set jobidwitoutspace=%initialDownloadFileName:~0,-1%.zip    
  set underscore=_
  set uploadfilejobid=%job_id:~0,-1%%underscore%%req_id%
  
:: Getting Job Id, System Salt Id and Extract file name from Extract Table Ends here


:: Deleting Extracted Files from Directory Starts here
echo
echo ========== Deleting Extracted Files from Directory ==========
echo
  del /s /q %deletedir%\*.*
  rd  %deletedir% /s /q
  md  %deletefolder%
  mkdir %createdir%
echo 
:: Deleting Extracted Files from Directory Ends here 
 
 
:: Extracting Files from S3 into Local Directory Starts here  
if %system_salt_id% == 0 goto:downloadfromS3WithOutSalt
goto:ExtractAndDownloadS3

: ExtractAndDownloadS3
echo
echo select salt from auditcompanion.system_salt where id=%system_salt_id%;> %mainpath%mysql.sql
CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt 
if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "salt=%%x" )
echo
goto:downloadfromS3WithSalt

: downloadfromS3WithOutSalt
echo
powershell.exe -NoProfile -NonInteractive -ExecutionPolicy ByPass -file %mainpath%DownloadS3Extract.ps1 %job_id% %initialDownloadFileName%%aws_region% %aws_access_key% %aws_secret_access_key% %aws_bucket_name%
if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
echo
goto:ExtractS3FilesWithOutSalt

: downloadfromS3WithSalt
echo
powershell.exe -NoProfile -NonInteractive -ExecutionPolicy ByPass -file %mainpath%DownloadS3Extract.ps1 %job_id% %initialDownloadFileName%%aws_region% %aws_access_key% %aws_secret_access_key% %aws_bucket_name%
if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
echo
goto:ExtractS3FilesWithSalt

: ExtractS3FilesWithOutSalt
echo
%mainpath%7-Zip\7z.exe e %zipfile%%jobidwitoutspace% -y -o%workdir% 
%mainpath%7-Zip\7z.exe e %workdir%%extract_file_name% -y -o%extracteddir% 
%mainpath%7-Zip\7z.exe e %extracteddir%*.zip -y -o%extractednewdir% 
echo
: ExtractS3FilesWithSalt
echo
%mainpath%7-Zip\7z.exe e %zipfile%%jobidwitoutspace% -y -o%workdir%
%mainpath%7-Zip\7z.exe e %workdir%%extract_file_name% -p%salt% -y -o%extracteddir%
%mainpath%7-Zip\7z.exe e %extracteddir%*.zip -y -o%extractednewdir%
echo
:: Extracting Files from S3 into Local Directory Ends here  
 
 
:: Retrieving Data from MySql Server Starts here
echo ========== Retrieving Data from MySql Server Starts here ==========
    
echo	
    echo SELECT rulebook_id FROM auditcompanion.analysis_request where id =%req_id%; > %mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "rulebook_id=%%x" )	
	
	echo
	  echo ========== Query 1 - Get rulebook List using Rulebook Id Starts Here ==========
		echo select name,description FROM auditcompanion.rulebook where id=%rulebook_id%;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_RuleBook.DAT
        if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing		
	  echo ========== Query 1 - Get rulebook List using Rulebook Id Ends Here ==========
    echo	
	  echo ========== Query 2 - Get all Risks of a Rulebook using rulebookId Starts Here ==========
		echo SELECT sap_risk_id, single_function_risk, risk_category, risk_level, description FROM risk r INNER JOIN rulebook_has_risk rhr ON rhr.risk_id = r.id WHERE rhr.rulebook_id = %rulebook_id% AND rhr.delete_flag = 0 AND r.delete_flag = 0 ORDER BY sap_risk_id;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_Risks.DAT		
	    if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	  echo ========== Query 2 - Get all Risks of a Rulebook using rulebookId Ends Here ==========
    echo	
      echo ========== Query 3 - Get Risks mapped to a Rulebook using rulebookId Starts Here ==========
		echo SELECT rb.name, sap_risk_id FROM risk r INNER JOIN rulebook rb ON rb.id =%rulebook_id% INNER JOIN rulebook_has_risk rhr ON rhr.risk_id = r.id WHERE rhr.rulebook_id = %rulebook_id% AND rhr.delete_flag = 0 AND r.delete_flag = 0 ORDER BY sap_risk_id;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_RuleBookRisk.DAT		
	    if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	  echo ========== Query 3 - Get Risks mapped to a Rulebook using rulebookId Ends Here ==========
	echo	
	  echo ========== Query 4 - Get all rulebook_has_risk mapping id to get job_functions Starts Here ==========		
		echo SELECT rhr.id FROM risk r INNER JOIN rulebook_has_risk rhr ON rhr.risk_id = r.id WHERE rhr.rulebook_id = %rulebook_id% and rhr.delete_flag = 0 and r.delete_flag = 0 ORDER BY sap_risk_id;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt		
        if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
		setlocal enableextensions disabledelayedexpansion
		set "first=1"
		(for /f "delims=" %%a in (%mainpath%mysql.txt) do (
		   if not defined first ( set /p"=,%%a" ) else ( set /p"=%%a" & set "first=" )
		)) <nul > %mainpath%output.txt 
			
		set rulebook_mapping_id=""
		for /f "tokens=*" %%a in (%mainpath%output.txt) do (
		  set rulebook_mapping_id=%%a
		)
	  echo ========== Query 4 - Get all rulebook_has_risk mapping id to get job_functions Ends Here ==========
	echo 	
	  echo ========== Query 5 - Get all Job function of a rulebook Starts Here ==========
		echo SELECT sap_job_function_id, description FROM job_function jf LEFT JOIN risk_has_job_function rhjf ON rhjf.job_function_id = jf.id WHERE jf.rulebook_id = %rulebook_id% or rhjf.risk_id IN (%rulebook_mapping_id%) AND rhjf.delete_flag =0 AND jf.delete_flag = 0 group by jf.sap_job_function_id;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_Function.DAT		
	    if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	  echo ========== Query 5 - Get all Job function of a rulebook Ends Here ==========
	echo 
	  echo ========== Query 6 - Get Job_Functions Mapped to a risk using rulebook_has_risk mapping id list Starts Here ==========		
		echo SELECT r.sap_risk_id, sap_job_function_id FROM job_function jf INNER JOIN risk_has_job_function rhjf ON rhjf.job_function_id = jf.id INNER JOIN rulebook_has_risk rhr on rhr.id = rhjf.risk_id INNER JOIN risk r on r.id = rhr.risk_id WHERE rhjf.risk_id IN(%rulebook_mapping_id%) AND rhjf.delete_flag =0 AND jf.delete_flag = 0 AND rhr.delete_flag = 0 AND r.delete_flag = 0;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_RiskFunction.DAT		
	    if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	 echo ========== Query 6 - Get Job_Functions Mapped to a risk using rulebook_has_risk mapping id list Ends Here ==========
	echo 
	  echo ========== Query 7 - Get Below query will give all risk_has_job_function mapping id to get transactions Starts Here ==========
	  echo %rulebook_id%
	  
		echo SELECT rhjf.id FROM risk r INNER JOIN rulebook_has_risk rhr ON rhr.risk_id = r.id INNER JOIN risk_has_job_function rhjf on rhjf.risk_id = rhr.id WHERE rhr.rulebook_id = %rulebook_id% and r.delete_flag = 0 and rhr.delete_flag = 0 and rhjf.delete_flag = 0 ORDER BY job_function_id;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt 		
        if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing		
		setlocal enableextensions disabledelayedexpansion
		set "first1=1"
		(for /f "delims=" %%a in (%mainpath%mysql.txt) do (
		   if not defined first1 ( set /p"=,%%a" ) else ( set /p"=%%a" & set "first1=" )
		)) <nul > %mainpath%output.txt 
			
		set risk_has_mapping_ids=""
		for /f "tokens=*" %%a in (%mainpath%output.txt) do (
		  set risk_has_mapping_ids=%%a
		)			
	  echo ========== Query 7 - Get Below query will give all risk_has_job_function mapping id to get transactions Ends Here ==========
    echo 
	  echo ========== Query 8 - Get Transactions using risk_has_job_function mapping id list Starts Here ==========
		echo SELECT sap_transaction_id, description FROM transactions t LEFT JOIN job_function_has_transaction jfht on jfht.transaction_id = t.id WHERE t.rulebook_id = %rulebook_id% or jfht.job_function_id IN (%risk_has_mapping_ids%) AND jfht.delete_flag =0 AND t.delete_flag = 0 group by t.sap_transaction_id;> %mainpath%mysql.sql		
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_Transactions.DAT	        		
	    if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	  echo ========== Query 8 - Get Transactions using risk_has_job_function mapping id list Ends Here ==========
	echo 
	  echo ========== Query 9 - Get Transactions using risk_has_job_function mapping id list FunctionTransactions.dat Starts Here ==========
		echo SELECT jf.sap_job_function_id, t.sap_transaction_id FROM transactions t INNER JOIN job_function_has_transaction jfht ON jfht.transaction_id = t.id INNER JOIN risk_has_job_function rhjf on rhjf.id = jfht.job_function_id INNER JOIN job_function jf on jf.id = rhjf.job_function_id WHERE jfht.job_function_id IN (%risk_has_mapping_ids%) AND jfht.delete_flag =0 AND t.delete_flag = 0;> %mainpath%mysql.sql
		CALL mysql -N %mysqlpath% < %mainpath%mysql.sql > %rss_std%SOD_MST_FunctionsTransactions.DAT		
	    if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	 echo ========== Query 9 - Get Transactions using risk_has_job_function mapping id list FunctionTransactions.dat Ends Here ==========
	echo 
echo ========== Retrieving Data from MySql Server Ends here ==========
:: Retrieving Data from MySql Server Ends here


:: Creating SAP Tables Structure definition and Importing data to respective table Starts here
echo
echo ========== Creating SAP Tables ==========
echo
    :: ------------------------------------ 
	if EXIST %extractednewdir%AGR_1016.def  (
	sqlcmd %auth% -Q "drop table AGR_1016;"
	sqlcmd %auth% -i "%extractednewdir%AGR_1016.def" 
	)
	echo
	set doscommandAGR_1016=%bcpcommand% Datamatrix_sapdb.dbo.AGR_1016 in  %extractednewdir%AGR_1016.DAT %copyauth% -c -T
	%doscommandAGR_1016%  
	echo
	:: ------------------------------------ 
	if EXIST %extractednewdir%AGR_1251.def  (
	sqlcmd %auth% -Q "drop table AGR_1251;"
	sqlcmd %auth% -i "%extractednewdir%AGR_1251.def" 
	) 
	echo
	set doscommandAGR_1251=%bcpcommand% Datamatrix_sapdb.dbo.AGR_1251 in  %extractednewdir%AGR_1251.DAT %copyauth% -c -T
	%doscommandAGR_1251%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%AGR_AGRS.def  (
	sqlcmd %auth% -Q "drop table AGR_AGRS;"
	sqlcmd %auth% -i "%extractednewdir%AGR_AGRS.def" 
	)
	echo
	set doscommandAGR_AGRS=%bcpcommand% Datamatrix_sapdb.dbo.AGR_AGRS in  %extractednewdir%AGR_AGRS.DAT %copyauth% -c -T
	 %doscommandAGR_AGRS%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%AGR_DEFINE.def  (
	sqlcmd %auth% -Q "drop table AGR_DEFINE;"
	sqlcmd %auth% -i "%extractednewdir%AGR_DEFINE.def" 
	)
	echo
	set doscommandAGR_DEFINE=%bcpcommand% Datamatrix_sapdb.dbo.AGR_DEFINE in  %extractednewdir%AGR_DEFINE.DAT %copyauth% -c -T
	 %doscommandAGR_DEFINE%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%AGR_PROF.def  (
	sqlcmd %auth% -Q "drop table AGR_PROF;"
	sqlcmd %auth% -i "%extractednewdir%AGR_PROF.def" 
	)
	echo
	set doscommandAGR_PROF=%bcpcommand% Datamatrix_sapdb.dbo.AGR_PROF in  %extractednewdir%AGR_PROF.DAT %copyauth% -c -T
	 %doscommandAGR_PROF%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%AGR_TCODES.def  (
	sqlcmd %auth% -Q "drop table AGR_TCODES;"
	sqlcmd %auth% -i "%extractednewdir%AGR_TCODES.def" 
	)
	echo
	set doscommandAGR_TCODES=%bcpcommand% Datamatrix_sapdb.dbo.AGR_TCODES in  %extractednewdir%AGR_TCODES.DAT %copyauth% -c -T
	 %doscommandAGR_TCODES%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%AGR_TEXTS.def  (
	sqlcmd %auth% -Q "drop table AGR_TEXTS;"
	sqlcmd %auth% -i "%extractednewdir%AGR_TEXTS.def" 
	)
	echo
	set doscommandAGR_TEXTS=%bcpcommand% Datamatrix_sapdb.dbo.AGR_TEXTS in  %extractednewdir%AGR_TEXTS.DAT %copyauth% -c -T
	 %doscommandAGR_TEXTS%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%AGR_USERS.def  (
	sqlcmd %auth% -Q "drop table AGR_USERS;"
	sqlcmd %auth% -i "%extractednewdir%AGR_USERS.def" 
	)
	echo
	set doscommandAGR_USERS=%bcpcommand% Datamatrix_sapdb.dbo.AGR_USERS in  %extractednewdir%AGR_USERS.DAT %copyauth% -c -T
	 %doscommandAGR_USERS%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%TOBJ.def  (
	sqlcmd %auth% -Q "drop table TOBJ;"
	sqlcmd %auth% -i "%extractednewdir%TOBJ.def" 
	)
	echo
	set doscommandTOBJ=%bcpcommand% Datamatrix_sapdb.dbo.TOBJ in  %extractednewdir%TOBJ.DAT %copyauth% -c -T
	 %doscommandTOBJ%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%TSTCV.def  (
	sqlcmd %auth% -Q "drop table TSTCV;"
	sqlcmd %auth% -i "%extractednewdir%TSTCV.def" 
	)
	echo
	set doscommandTSTCV=%bcpcommand% Datamatrix_sapdb.dbo.TSTCV in  %extractednewdir%TSTCV.DAT %copyauth% -c -T
	 %doscommandTSTCV%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%USR21.def  (
	sqlcmd %auth% -Q "drop table USR21;"
	sqlcmd %auth% -i "%extractednewdir%USR21.def" 
	)
	echo
	set doscommandUSR21=%bcpcommand% Datamatrix_sapdb.dbo.USR21 in  %extractednewdir%USR21.DAT %copyauth% -c -T
	 %doscommandUSR21%
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%UST04.def  (
	sqlcmd %auth% -Q "drop table UST04;"
	sqlcmd %auth% -i "%extractednewdir%UST04.def" 
	)
	echo
	set doscommandUST04=%bcpcommand% Datamatrix_sapdb.dbo.UST04 in  %extractednewdir%UST04.DAT %copyauth% -c -T
	 %doscommandUST04%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%UST10C.def  (
	sqlcmd %auth% -Q "drop table UST10C;"
	sqlcmd %auth% -i "%extractednewdir%UST10C.def" 
	)
	echo
	set doscommandUST10C=%bcpcommand% Datamatrix_sapdb.dbo.UST10C in  %extractednewdir%UST10C.DAT %copyauth% -c -T
	 %doscommandUST10C%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%UST10S.def (
	sqlcmd %auth% -Q "drop table UST10S;"
	sqlcmd %auth% -i "%extractednewdir%UST10S.def" 
	)
	echo
	set doscommandUST10S=%bcpcommand% Datamatrix_sapdb.dbo.UST10S in  %extractednewdir%UST10S.DAT %copyauth% -c -T
	%doscommandUST10S%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%UST12.def (
	sqlcmd %auth% -Q "drop table UST12;"
	sqlcmd %auth% -i "%extractednewdir%UST12.def" 
	)
	echo
	set doscommandUST12=%bcpcommand% Datamatrix_sapdb.dbo.UST12 in  %extractednewdir%UST12.DAT %copyauth% -c -T
	%doscommandUST12%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%V_USERNAME.def (
	sqlcmd %auth% -Q "drop table V_USERNAME;"
	sqlcmd %auth% -i "%extractednewdir%V_USERNAME.def" 
	)
	echo
	set doscommandV_USERNAME=%bcpcommand% Datamatrix_sapdb.dbo.V_USERNAME in  %extractednewdir%V_USERNAME.DAT %copyauth% -c -T
	%doscommandV_USERNAME%	
	echo
	:: ------------------------------------
	if EXIST %extractednewdir%USR02.def (
	sqlcmd %auth% -Q "drop table USR02;"
	sqlcmd %auth% -i "%extractednewdir%USR02.def" 
	)
	echo
	set doscommandUSR02=%bcpcommand% Datamatrix_sapdb.dbo.USR02 in  %extractednewdir%USR02.DAT %copyauth% -c -T
	%doscommandUSR02%	
	echo
	:: ------------------------------------
	::MASTER TABLES
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_Transactions.def (
	sqlcmd %auth% -Q "drop table SOD_MST_Transactions;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_Transactions.def" 
	)
	echo
	set doscommandSOD_MST_Transactions=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_Transactions in  %rss_std%SOD_MST_Transactions.DAT %copyauth% -c -T
	%doscommandSOD_MST_Transactions%
	echo
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_Function.def (
	sqlcmd %auth% -Q "drop table SOD_MST_Function;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_Function.def" 
	)
	echo
	set doscommandSOD_MST_Function=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_Function in  %rss_std%SOD_MST_Function.DAT %copyauth% -c -T
	%doscommandSOD_MST_Function%	
	echo
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_FunctionsTransactions.def (
	sqlcmd %auth% -Q "drop table SOD_MST_FunctionsTransactions;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_FunctionsTransactions.def" 
	)
	echo
	set doscommandSOD_MST_FunctionsTransactions=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_FunctionsTransactions in  %rss_std%SOD_MST_FunctionsTransactions.DAT %copyauth% -c -T
	%doscommandSOD_MST_FunctionsTransactions%	
	echo
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_Risks.def (
	sqlcmd %auth% -Q "drop table SOD_MST_Risks;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_Risks.def" 
	)
	echo
	set doscommandSOD_MST_Risks=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_Risks in  %rss_std%SOD_MST_Risks.DAT %copyauth% -c -T
	%doscommandSOD_MST_Risks%	
	echo
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_RiskFunction.def (
	sqlcmd %auth% -Q "drop table SOD_MST_RiskFunction;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_RiskFunction.def" 
	)
	echo
	set doscommandSOD_MST_RiskFunction=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_RiskFunction in  %rss_std%SOD_MST_RiskFunction.DAT %copyauth% -c -T
	%doscommandSOD_MST_RiskFunction%	
	echo
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_RuleBook.def (
	sqlcmd %auth% -Q "drop table SOD_MST_RuleBook;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_RuleBook.def" 
	)
	echo
	set doscommandSOD_MST_RuleBook=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_RuleBook in  %rss_std%SOD_MST_RuleBook.DAT %copyauth% -c -T
	%doscommandSOD_MST_RuleBook%	
	echo
	:: ------------------------------------
	if EXIST %rss_std%SOD_MST_RuleBookRisk.def (
	sqlcmd %auth% -Q "drop table SOD_MST_RuleBookRisk;"
	sqlcmd %auth% -i "%rss_std%SOD_MST_RuleBookRisk.def" 
	)
	echo
	set doscommandSOD_MST_RuleBookRisk=%bcpcommand% Datamatrix_sapdb.dbo.SOD_MST_RuleBookRisk in  %rss_std%SOD_MST_RuleBookRisk.DAT %copyauth% -c -T
	%doscommandSOD_MST_RuleBookRisk%	
echo
goto:Analyze
:: Creating SAP Tables Structure definition and Importing data to respective table Ends here


:: Analysing Status of Request and generating CSV files and uploading into S3 Starts here
echo
echo ========== Analyzing Status of Request and generating CSV files and uploading into S3 ==========
echo
:Analyze 
    echo
    echo SELECT name FROM auditcompanion.rulebook where id =%rulebook_id%; > %mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "rulebook_name=%%x" ) 
	echo
    sqlcmd %auth% -Q "EXEC SOD2BAnalysis_Raju '%rulebook_name%'"	    
    echo
    echo SELECT is_free_trial_request FROM auditcompanion.analysis_request where id =%req_id%; > %mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "is_free_trial_request=%%x" )
	echo
    set doscommandA_Management_Summary="select 'Description' as Description,'TotalFound' as TotalFound,'TotalExposure' as TotalExposure,'ExposureExample' as ExposureExample union all select Description,cast(ms.TotalFound as varchar),cast(ms.TotalExposure as varchar),ms.ExposureExample from Datamatrix_sapdb.dbo.A_Management_Summary ms"
    %bcpcommand% %doscommandA_Management_Summary% queryout %reports%Management_Summary.csv %copyauth% -c -t, -T	
    echo  
    IF %is_free_trial_request%==0 goto:CreateCSVFiles
    goto:UploadToS3
   
:CreateCSVFiles
   ::Generating CSV Files
   echo
   echo ========== Generating CSV Files ==========
   echo
   set doscommandA_GlobalRisk="select 'Role' as Role,'RoleDesc' as RoleDesc,'CompRole' as CompRole,'CompRoleDesc' as CompRoleDesc,'Profile1' as Profile1,'Object' as Object,'Field','VON' as Field,'BIS' as BIS union all select Role,RoleDesc,CompRole,CompRoleDesc,Profile1,Object,Field,VON,BIS from Datamatrix_sapdb.dbo.A_GlobalRisk"
   %bcpcommand% %doscommandA_GlobalRisk% queryout %reports%Global_Risk.csv %copyauth% -c -t, -T    
   echo  
   set doscommandA_UserGlobalRisk="select 'BNAME' as BNAME,'GLTGV' as GLTGV,'GLTGB' as GLTGB,'USTYPDESC' as USTYPDESC,'Role' as Role,'CompRole' as CompRole,'Profile1' as Profile1,'Object' as Object,'Field' as Field,'VON' as VON,'BIS' as BIS union all select BNAME,GLTGV,GLTGB,USTYPDESC,Role,CompRole,Profile1,Object,Field,VON,BIS from Datamatrix_sapdb.dbo.A_UserGlobalRisk"
   %bcpcommand% %doscommandA_UserGlobalRisk% queryout %reports%User_Global_Risk.csv %copyauth% -c -t, -T
   echo
   set doscommandA_UserA_CompRoleConflict="select 'CompRole' as CompRole,'CompRoleDesc' as CompRoleDesc,'Function' as [Function],'Role' as Role,'TcodesAll' as TcodesAll,'ConflictingTcodesAll' as ConflictingTcodesAll,'ConflictingRole' as ConflictingRole,'ConflictingFunction' as ConflictingFunction union all select crc.CompRole,crc.CompRoleDesc,crc.[Function],crc.Role,crc.TcodesAll,crc.ConflictingTcodesAll,crc.ConflictingRole,crc.ConflictingFunction from Datamatrix_sapdb.dbo.A_CompRoleConflict crc"
   %bcpcommand% %doscommandA_UserA_CompRoleConflict% queryout %reports%Comp_Role_Conflict.csv %copyauth% -c -t, -T   
   echo
   set doscommandA_SingleRoleConflict="select 'Role' as Role,'RoleDesc' as RoleDesc,'Function' as [Function],'TcodesAll' as TcodesAll,'ConflictingTcodesAll' as ConflictingTcodesAll,'ConflictingFunction' as ConflictingFunction union all select Role,RoleDesc,[Function],TcodesAll,ConflictingTcodesAll,ConflictingFunction from Datamatrix_sapdb.dbo.A_SingleRoleConflict"
   %bcpcommand% %doscommandA_SingleRoleConflict% queryout %reports%Single_Role_Conflict.csv %copyauth% -c -t, -T 
   echo
   set doscommandA_UserTMP_SingleRoleHelper="select 'RiskID' as RiskID,'FunctionID1' as FunctionID1,'Role1' as Role1,'TCode1' as TCode1,'FunctionID2' as FunctionID2,'TCode2' as TCode2 union all select RiskID,FunctionID1,Role1,TCode1,FunctionID2,TCode2 from Datamatrix_sapdb.dbo.TMP_SingleRoleHelper"
   %bcpcommand% %doscommandA_UserTMP_SingleRoleHelper% queryout %reports%Single_Role_Helper.csv %copyauth% -c -t, -T
   echo
   set doscommandA_UsersWithConflicts="select 'BNAME' as BNAME,'GLTGV' as GLTGV,'GLTGB' as GLTGB,'USTYPDESC' as USTYPDESC,'CompRole' as CompRole,'CompRoleDesc' as CompRoleDesc,'Role' as Role,'Function' as [Function],'TcodesAll' as TcodesAll,'ConflictingTcodesAll' as ConflictingTcodesAll,'ConflictingRole' as ConflictingRole,'ConflictingFunction' as ConflictingFunction union all select BNAME,GLTGV,GLTGB,USTYPDESC,CompRole,CompRoleDesc,Role,[Function],TcodesAll,ConflictingTcodesAll,ConflictingRole,ConflictingFunction from Datamatrix_sapdb.dbo.A_UsersWithConflicts"
   %bcpcommand% %doscommandA_UsersWithConflicts% queryout %reports%Users_With_Conflicts.csv %copyauth% -c -t, -T
   goto:UploadToS3

:UploadToS3
    ::Uploading reports to S3
    echo
	echo ========== Uploading reports to S3 ==========
    powershell.exe -NoProfile -NonInteractive -ExecutionPolicy ByPass -file %mainpath%UploadToS3.ps1 %uploadfilejobid% %aws_region% %aws_access_key% %aws_secret_access_key% %aws_bucket_name%
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	echo
	
    :: Updating Status for Mysql Tables after uploading reports to S3
	echo
    echo ========== Updating Status for Mysql Tables after uploading reports to S3 ==========
    echo
    echo SELECT param_value FROM auditcompanion.system_param where param_key ='Report expiry duration'; > %mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql > %mainpath%mysql.txt
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	for /f "skip=1 tokens=* delims=" %%x in (%mainpath%mysql.txt)  do (set "param_value=%%x" )  
	echo
	echo UPDATE analysis_request set status='Report Created',file_expire_dt_tm=DATE_ADD(NOW(), INTERVAL %param_value% day),file_created_dt_tm=NOW() where id =%req_id%; > %mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql  	
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'Management_Summary.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	IF %is_free_trial_request%==0 goto:InsertIntoAnalystReports
	goto:ErrorQuit

:InsertIntoAnalystReports
    :: Inserting into Analysis Request Report File table
    echo
    echo ========== Inserting into Analysis Request Report File table ==========
    echo	
    echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'Global_Risk.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing	
	echo
	echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'Comp_Role_Conflict.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing	
	echo
	echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'Single_Role_Conflict.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	echo
	echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'Single_Role_Helper.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	echo
	echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'User_Global_Risk.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	echo
	echo insert into auditcompanion.analysis_request_report_file(analysis_request_id,file_name,created_dt_tm,updated_dt_tm,delete_flag) values (%req_id%,'Users_With_Conflicts.csv',NOW(),NOW(),0);>%mainpath%mysql.sql
	CALL mysql %mysqlpath% < %mainpath%mysql.sql
	if NOT %ERRORLEVEL% == 0 goto:ErrorInProcessing
	echo
	goto:SuccessQuit	
	
:: Analysing Status of Request and generating CSV files and uploading into S3 Ends here


:SuccessQuit
  echo ====================
  echo Successfully completed processing.....
  echo ====================
  pause
  
:ErrorInProcessing
  :: Generic function that updates analysis_request tabled with 'failed' status in case script fails to execute successfully Starts here
  echo UPDATE auditcompanion.analysis_request set status='Failed' where id =%req_id%; > %mainpath%mysql.sql
  CALL mysql %mysqlpath% < %mainpath%mysql.sql
  echo ====================
  echo Error while processing.....
  echo ====================
  pause
  :: Generic function that updates analysis_request tabled with 'failed' status in case script fails to execute successfully Ends here
  
:ErrorQuit
  echo ====================
  echo Error while processing.....
  echo ====================
  pause
   
:EOF