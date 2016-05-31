namespace :chalet do

  task :htaccess do
    on roles(:all) do
      within fetch(:release_path) do
        execute :mv, "web/htaccess.#{fetch(:symfony_env)}.dist", 'web/.htaccess'
      end
    end
  end

  task :clear_cache do
    invoke 'symfony:console', 'cache:clear', "--env=#{fetch(:symfony_env)}"
  end

  task :warmup_cache do
    invoke 'symfony:console', 'cache:warmup', "--env=#{fetch(:symfony_env)}"
  end

  task :dump_routes do
    invoke 'symfony:console', 'fos:js-routing:dump', "--env=#{fetch(:symfony_env)}"
  end

  task :dump_assetic do
    invoke 'symfony:console', 'cache:clear', "--env=#{fetch(:symfony_env)}"
    invoke 'symfony:console', 'assetic:dump', "--env=#{fetch(:symfony_env)}"
  end

  task :import_autocomplete do
    on roles(:all) do
      within fetch(:release_path) do
        execute 'bin/import-autocomplete', '-w C', "--env #{fetch(:symfony_env)}"
      end
    end
  end

  task :upload_build_files do
    system("gulp build")
    on roles(:all) do
      within fetch(:release_path) do
        execute "mkdir -p #{fetch(:release_path)}/web/bundles/app/css/build"
        execute "mkdir -p #{fetch(:release_path)}/web/bundles/app/img/spritesheets"
      end
      upload! "./web/bundles/app/css/build/prod.css", "#{fetch(:release_path)}/web/bundles/app/css/build/"
      upload! "./web/bundles/app/js/price_table.js", "#{fetch(:release_path)}/web/bundles/app/js/"
      Dir.glob("./web/bundles/app/img/spritesheets/*.png") do |filename|
        upload! "#{filename}", "#{fetch(:release_path)}/web/bundles/app/img/spritesheets/"
      end
    end
  end

end

# overriding default cleanup task to use sudo
Rake::Task['deploy:cleanup'].clear_actions
namespace :deploy do
  task :cleanup do
    on release_roles :all do |host|
      releases = capture(:ls, '-xtr', releases_path).split
      if releases.count >= fetch(:keep_releases)
        info t(:keeping_releases, host: host.to_s, keep_releases: fetch(:keep_releases), releases: releases.count)
        directories = (releases - releases.last(fetch(:keep_releases)))
        if directories.any?
          directories_str = directories.map do |release|
            releases_path.join(release)
          end.join(" ")
          execute :sudo, :rm, '-rf', directories_str
        else
          info t(:no_old_releases, host: host.to_s, keep_releases: fetch(:keep_releases))
        end
      end
    end
  end
end
