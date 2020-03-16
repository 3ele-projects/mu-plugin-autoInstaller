import os
import urllib.request, json 
with urllib.request.urlopen("http://json.testing.threeelements.de/data.json") as url:
    configdata = json.loads(url.read().decode())


#get download wp-core


""" path = "wp"
try:
    os.mkdir(path)
except OSError:
    print ("Creation of the directory %s failed" % path)
else:
    print ("Successfully created the directory %s " % path)
    path = os.getcwd()+'/wp'
    os.chdir(path)

os.system('wp core download') """


import paramiko
import sys
#global paths
path = sys.path[0]
import os
class wp_connector:
    def execute_wp_cli(self, hostname, username, ssh_key, command):
            
            self.hostname = hostname
            self.ssh_key = ssh_key
            self.command = command
            self.username = username
            print (ssh_key)
            try:
                ssh_client = paramiko.SSHClient()
                ssh_client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
                ssh_client.connect(hostname=self.hostname, username=self.username, key_filename=self.ssh_key)
                stdin, stdout,stderr  = ssh_client.exec_command(self.command)
                #print(stdout.read())
                print(stderr.read())
                    
                msg = stderr.read().decode('utf-8')
                return msg
            except (RuntimeError, TypeError, NameError) as e :
                    print (e)
                    msg = e
             
            finally:
                ssh_client.close()
                

    


wp = wp_connector()
wp_instance = {}
home = os.getenv("HOME")
wp_instance['host'] =  'w0153fc1.kasserver.com'
wp_instance['user'] =  'ssh-w01a2cf9'
ssh_key = home+'/.ssh/id_rsa.pub'

wp_path = '/www/htdocs/w01a2cf9/wpInstance/'

#command = 'wp core download --path="' + wp_path + '"'

#wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)

for theme in configdata['themes']:
    command = 'wp theme install ' + theme['name']+' --path="' + wp_path + '"'
    wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)

print (configdata['plugins'])
for plugin in configdata['plugins']:
    command = 'wp plugin install ' + plugin['path'] +' --path="' + wp_path + '"'
    wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)

command = 'wp core update  --path="' + wp_path + '"'
wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)
command = 'wp plugin update --all  --path="' + wp_path + '"'
wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)
command = 'wp theme update --all  --path="' + wp_path + '"'
wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)
from datetime import datetime
from datetime import date

today = date.today()
date = today.strftime("%Y-%m-%d")



command = 'cd ' +wp_path+' && zip -r wordpress-installer-'+date+'.zip .'
print (command)
wp.execute_wp_cli(wp_instance['host'], wp_instance['user'], ssh_key, command)

#http://download.testing.threeelements.de/wordpress-installer-2020-03-15.zip