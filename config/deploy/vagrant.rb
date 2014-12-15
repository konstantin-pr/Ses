server '127.0.0.1', :web, :admin
set :ssh_options, {port: 2222, keys: ['~/.vagrant.d/insecure_private_key']}

set :user, 'vagrant'
set :password, 'vagrant'

set :deploy_to, "/vagrant/htdocs/#{application}"
set :scripts_path, "#{deploy_to}/application/Scripts"

namespace :deploy do
end