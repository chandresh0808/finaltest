
param ([string]$w,[string]$x,[string]$region,[string]$S3AccessID,[string]$S3Key,[string]$S3Bucket)

import-module "C:\Program Files (x86)\AWS Tools\PowerShell\AWSPowerShell\AWSPowerShell.psd1"
 $AWSregion = "us-east-1"
 Set-DefaultAWSRegion $region
 $AWSserviceURL="https://s3-$region.amazonaws.com"
 $config=New-Object Amazon.S3.AmazonS3Config
 $config.ServiceURL = $AWSserviceURL
 $filename=$x+".zip"
 $filepath="C:\AuditCompanionScripts\auditcompanion-portal\DBSS"
 $S3Client=[Amazon.AWSClientFactory]::CreateAmazonS3Client($secretKeyID, $secretAccessKeyID, $config)
 Set-AWSCredentials -AccessKey $S3AccessID -SecretKey $S3Key
 Read-S3object -BucketName $S3Bucket -Key requests\$w\$filename  -file  $filepath\$filename
