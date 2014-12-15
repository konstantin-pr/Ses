namespace :db do
  desc "Create project database (doctrine-cli)"
  task :create, :roles => :admin do
    run "cd #{scripts_path} && ./doctrine-cli orm:stuzo:create-db"
  end

  desc "Drop project database (doctrine-cli)"
  task :drop, :roles => :admin do
    run "cd #{scripts_path} && ./doctrine-cli orm:stuzo:drop-db"
  end

  desc "Clear Doctrine cache"
  task :clear_cache, :roles => :admin do
    begin
        run "cd #{scripts_path} && ./doctrine-cli orm:clear-cache:result"
    rescue
        deploy.restart # Clean APC cache by restarting Apache
    end
  end

  desc "Validate project database schema, checks mapping files and compares it with database schema (doctrine-cli)"
  task :validate, :roles => :admin do
    run "cd #{scripts_path} && ./doctrine-cli orm:validate-schema"
  end

  desc "Create project database tables (doctrine-cli)"
  task :update, :roles => :admin do
    transaction do
      clear_cache
      run "cd #{scripts_path} && ./doctrine-cli orm:schema-tool:update --force"
    end
  end

  desc "Test project database tables (doctrine-cli)"
  task :test_schema, :roles => :admin do
    transaction do
      clear_cache
      result = capture "cd #{scripts_path} && ./doctrine-cli orm:schema-tool:update --dump-sql"
      if result.length > 2
      	Capistrano::CLI.ui.say %Q(
#{colorful("="*70, 33)}
#{colorful("Database schema is not up to date!!!", 31)}
#{colorful(result, 32)}
#{colorful("="*70, 33)}
      	)
      end
    end
  end

  desc "Import SQL files from ./application/models/seeds/*.sql"
  task :seed, :roles => :admin do
    run "cd #{scripts_path} && ./doctrine-cli  dbal:import #{scripts_path}/../models/seeds/*.sql"
  end

  desc "Import SQL files from ./application/models/seeds/*.sql"
  task :seeds, :roles => :admin do
    seed
  end
end