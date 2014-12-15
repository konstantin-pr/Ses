set :branch, "develop"

#https://wiki.stuzo.com/index.php/Certificate_based_deployment
set :ssh_options, { :forward_agent => true }
set :gateway, "ec2-23-21-146-225.compute-1.amazonaws.com"

set :apache_https_path, '/var/www'

# You can list several recipients, e.g.
# set :email_to, 'John Doe <john.doe@stuzo.com>, Jenny Doe <jenny.doe@stuzo.com>'
# set :email_to, [role_qa]

EC2_ADMIN_SERVER = "ec2-23-21-146-225.compute-1.amazonaws.com"

role(:web) { web_servers(stage.to_s()) << EC2_ADMIN_SERVER }
role :admin, EC2_ADMIN_SERVER

set :keep_releases, 4

namespace :deploy do
  after 'deploy:create_symlink', 'deploy:configure_https'
  # Left for compatibility with older version of capistrano
  after 'deploy:symlink', 'deploy:configure_https'
  before 'deploy:delete', 'deploy:delete_https'
  after 'deploy:restart', 'deploy:upgrade_ec2_image', 'deploy:cleanup'
#  after 'deploy', 'deploy:email_notify'
end