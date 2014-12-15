server 'office.stuzo.net', :web, :admin

set :user, 'web'
set :password, 'SKFsdgf304o3'

namespace :deploy do
    task :restart, :roles => :web do
        # Override, no need to restart Apache
    end
end