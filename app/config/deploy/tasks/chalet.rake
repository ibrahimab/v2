namespace :chalet do
  task :htaccess do
    on roles(:app) do
      within release_path do
        execute "cp web/htaccess.#{fetch(:symfony_env)}.dist web/.htaccess"
      end
    end
  end
end