# config valid only for current version of Capistrano
lock '3.4.0'

ask :symfony_env, 'stag'

set :app_path, 'app'
set :web_path, 'web'
set :log_path, fetch(:app_path) + '/logs'
set :cache_path, fetch(:app_path) + '/cache'
set :app_config_path, fetch(:app_path) + '/config'

set :application, 'chalet-v2'
set :repo_url, 'git@github.com:Chalet/chalet-v2.git'

# Default branch is :master
ask :branch, `git tag`.split("\n").last.strip

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/var/www/new.chalet.nl'

set :tmp_dir, fetch(:deploy_to) + '/tmp'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
set :linked_files, %w{app/config/parameters.yml}

# Default value for linked_dirs is []
set :linked_dirs, [fetch(:log_path), fetch(:web_path) + '/uploads']

set :file_permissions_paths, [fetch(:log_path), fetch(:cache_path)]
set :file_permissions_users, ['www-data']

set :webserver_user, 'www-data'

set :permission_method, :chmod
set :use_set_permissions, true

set :symfony_console_path, fetch(:app_path) + '/console'
set :symfony_console_flags, '--no-debug'

set :assets_install_path, fetch(:web_path)
set :assets_install_flags, '--symlink'
set :assetic_dump_flags, ''

set :composer_install_flags, '--no-dev --optimize-autoloader -vvv'
set :composer_roles, :all
set :composer_working_dir, -> { fetch(:release_path) }
set :composer_dump_autoload_flags, '--optimize'
set :composer_download_url, 'https://getcomposer.org/installer'

SSHKit.config.command_map[:composer] = "COMPOSER_CACHE_DIR=#{fetch(:tmp_dir)}/.composer php #{shared_path.join('composer.phar')}"

# Default value for default_env is {}
# set :default_env, { "SYMFONY_ENV" => "stag" }

# Default value for keep_releases is 5
set :keep_releases, 3

namespace :deploy do

  after :starting, 'composer:install_executable'

  after :updated, 'chalet:htaccess'

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end
end
