namespace :chalet do
  task :htaccess do
    on roles(:all) do
      within release_path do
        print "\n\n\n\n\n\n\nTesting\n\n\n\n\n\n\n"
        execute :cp, "web/htaccess.#{fetch(:symfony_env)}.dist", "web/.htaccess"
      end
    end
  end
end