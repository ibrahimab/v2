namespace :chalet do
  task :htaccess do
    on roles(:all) do
      within fetch(:release_path) do
        execute :mv, "web/htaccess.#{fetch(:symfony_env)}.dist", "web/.htaccess"
      end
    end
  end
  task :symlink_old_classes do
    on roles(:all) do
      within fetch(:release_path) do
        execute :ln, "-s", fetch(:old_classes_path), fetch(:release_path) + 'src/old-classes'
      end
    end
  end
end

namespace :deploy do
  # overriding default cleanup task
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
