# Name of application. Will be used as folder name
# set :application, "capistranotest"
set :application, "oralb"

# Github repository

set :repository,  "git@bitbucket.org:stuzo/pg-oralb-tb-challenge.git"

# Version of Stuzo library used in this project, e.g. "0.6.0"
set :stuzo_lib, "0.7.0"

# Set up roles, determine who works on the project
# E.g.
#   set :role_qa, [dashaO, olgaS]
#   set :role_dev, [evgenM, vovaL]
# See config/employees.rb for contact variables
set :role_qa, [dashaO]
set :role_pm, [kevinB]
set :role_dev, [kostiaU,oleksiyN]

###########################
# DO NOT CHANGE FROM HERE
###########################

default_run_options[:pty] = true

set(:deploy_to) { "/var/apps/#{application}" }
set :apache_path, "/var/www2"
# set :stuzo_lib_path, stuzo_lib.empty? ? "#{apache_path}/Stuzo" : "#{apache_path}/Releases/#{stuzo_lib}/Stuzo"
set(:scripts_path) { "#{deploy_to}/current/application/Scripts" }

set :stages, %w(development vagrant testing staging production)
set :default_stage, 'development'

set :use_sudo, false
# Use "copy" when wants to use git locally and transfer .tar.gz to server
# Use "export" to do "git clone" every time
# Use "remote_cache" to speed up "git clone"
set :deploy_via, :export

set :scm, :git
set :branch, "develop"
set :copy_exclude, %w(.git)
set :confirm_stages, 'production'

namespace :deploy do
  task :configure_apache, :roles => :web do
    transaction do
      # Create symbolic link in Apache WWW folder
      run "ln -nfs #{deploy_to}/current #{apache_path}/#{application}"
    end
  end

  task :configure_libraries, :roles => :web do
    transaction do
      # Create library folder if necessary
      run "mkdir -p #{latest_release}/library"

      # Create symbolic link to Stuzo library
      # run "ln -nfs #{stuzo_lib_path} #{latest_release}/library/Stuzo"

      # Create branch file
      run "touch #{latest_release}/#{branch.sub('/','-')}.branch"
      run "touch #{latest_release}/#{release_name}.version"
    end
  end

  task :finalize_update, :roles => :web do
  end

  desc 'Delete application Apache folder (not deployment folders)'
  task :delete, :roles => :web do
    run "rm #{apache_path}/#{application}"
  end

  desc 'Gracefully restart Apache2 on web servers'
  task :restart, :roles => :web do
    # Restart Apache to clear APC cache
    sudo "apache2ctl graceful"
  end

  desc 'Guaranteed restart of Apache service'
  task :force_restart, :roles => :web do
    # May be needed to immediately clean APC cache
    sudo "apache2ctl restart"
  end

  task :configure_https, :roles => :web do
    run "ln -nfs #{apache_path}/#{application} #{apache_https_path}/#{application}"
  end

  task :delete_https, :roles => :web do
    run "rm #{apache_https_path}/#{application}"
  end

  task :upgrade_ec2_image, :roles => :admin do
    upgrade_image(stage)
  end

  after 'deploy:create_symlink', 'deploy:configure_apache', 'deploy:configure_libraries'
  before 'deploy:restart', 'assets:deploy'
end
