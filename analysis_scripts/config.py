__author__ = 'piyush'

# Database configuration
# db_host = "app.staging.auditcompanion.biz"
db_host = "54.172.131.179"
#db_host = "54.144.146.130"
db_user = "auditcompanion"
db_password = "audit098$"
# db_password = "toor"
db_database = "auditcompanion"


# AWS credentials
aws_region = "us-east-1"
aws_access_key = "AKIAIYUMURKMYMJCV5FQ"
aws_secret_access_key = "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"
aws_bucket_name = "devel.wadyaknow"

# Spot instance configuration
#image_id = "ami-77de221c"
image_id  = "ami-9fb710f4"
spot_request_region = "us-east-1a"
instance_type = "m3.medium"
#instance_type = "t1.micro"

number_of_instances = 1
product_desc = "Windows (Amazon VPC)"
# product_desc = "Windows"
subnet_id = 'subnet-2765e40c'
#subnet_id = 'subnet-a3d3f999'
security_group_ids = ['sg-23485947']
# security_group_ids = ['sg-23485947']
dry_run = False

#SES config
smtp_server = "email-smtp.us-east-1.amazonaws.com"
smtp_port = "587"
smtp_username = "AKIAIOQYAVETI2JFV6UASMTP"
smtp_password = "Ao8nITllT5hJZQMhrWC5YsBNAhVODF4EJ4ng5f861mJ2"
smtp_transport_layer_security = True
from_mail_address = "support@auditcompanion.biz"