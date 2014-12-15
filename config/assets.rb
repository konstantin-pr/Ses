namespace :assets do

    ASSETS_FILE = 'application/tmp/assets.tar.gz'

    desc "Prepare assets (rebuild, minify, archive)"
    task :prepare do
        # for full rebuild run
        # run_locally 'grunt rebuild'
        # (it is usually not needed, because libs are not changed)
        run_locally "grunt build"
        run_locally "tar cfz #{ASSETS_FILE} public/js/bin public/css/bin application/Views/*.min.php"
    end

    desc 'Upload minified assets files'
    task :upload, :roles => :web do
        top.upload(File.new(ASSETS_FILE), "#{deploy_to}/current/assets.tar.gz")
    end

    desc 'Unpack archive on each web server'
    task :deliver, :roles => :web do
        run "cd #{deploy_to}/current && tar xzf assets.tar.gz"
    end

    desc 'Clean temporary archive files'
    task :clean, :roles => :web do
        run "cd #{deploy_to}/current && rm assets.tar.gz"
        run_locally "rm #{ASSETS_FILE}"
    end

    desc 'Deploy assets (common task)'
    task :deploy, :roles => :web do
        prepare
        upload
        deliver
        clean
    end
end