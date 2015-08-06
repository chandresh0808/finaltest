
param ([string]$w,[string]$region,[string]$S3AccessID,[string]$S3Key,[string]$S3Bucket)
import-module "C:\Program Files (x86)\AWS Tools\PowerShell\AWSPowerShell\AWSPowerShell.psd1"
 $AWSregion = "us-east-1"
 Set-DefaultAWSRegion $region
 $AWSserviceURL="https://s3-$region.amazonaws.com"
 $config=New-Object Amazon.S3.AmazonS3Config
 $config.ServiceURL = $AWSserviceURL
 $filepath="C:\AuditCompanionScripts\auditcompanion-portal\Reports"
 $destpath="Completed"
 $S3Client=[Amazon.AWSClientFactory]::CreateAmazonS3Client($secretKeyID, $secretAccessKeyID, $config)
 Set-AWSCredentials -AccessKey $S3AccessID -SecretKey $S3Key
 
 foreach ($i in Get-ChildItem  $filepath) 
{ 
  # Write the file to S3 and add the filename to a collection.
  Write-S3Object -BucketName $S3Bucket -Key $destpath\$w\$i -File $i.FullName
}
