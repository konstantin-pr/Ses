set :application, "#{application}-testing"
set :branch, "feature/minimize-assets"

#https://wiki.stuzo.com/index.php/Certificate_based_deployment
set :ssh_options, { :forward_agent => true }
set(:gateway) { "ec2-23-21-146-225.compute-1.amazonaws.com" }

set :apache_https_path, '/var/www'

EC2_ADMIN_SERVER = "ec2-23-21-146-225.compute-1.amazonaws.com"

role(:web) { web_servers(stage.to_s()) << EC2_ADMIN_SERVER }
role :admin, EC2_ADMIN_SERVER

set :keep_releases, 4

namespace :deploy do
  desc 'Gracefully restart Apache2 on web servers'
  task :restart, :roles => :web do
    # Restart Apache to clear APC cache
    sudo "apache2ctl-php5.5-testing graceful"
  end

  after 'deploy:create_symlink', 'deploy:configure_https'
  before 'deploy:delete', 'deploy:delete_https'
  after 'deploy:restart', 'deploy:upgrade_ec2_image', 'deploy:cleanup'
#  after 'deploy', 'deploy:email_notify'
end