set :deploy_config_path, 'app/config/deploy/deploy.rb'
set :stage_config_path,  'app/config/deploy/stages'

# Load DSL and set up stages
require 'capistrano/setup'

# Include default deployment tasks
require 'capistrano/deploy'

# Include git submodule strategy
require 'capistrano/git-submodule-strategy'

# Include hipchat capistrano links
require 'hipchat/capistrano'

# loading environment variables
require 'dotenv'
Dotenv.load

# Include tasks from other gems included in your Gemfile
#
# For documentation on these, see for example:
#
#   https://github.com/capistrano/rvm
#   https://github.com/capistrano/rbenv
#   https://github.com/capistrano/chruby
#   https://github.com/capistrano/bundler
#   https://github.com/capistrano/rails
#   https://github.com/capistrano/passenger
#
# require 'capistrano/rvm'
# require 'capistrano/rbenv'
# require 'capistrano/chruby'
# require 'capistrano/bundler'
# require 'capistrano/rails/assets'
# require 'capistrano/rails/migrations'
# require 'capistrano/passenger'
require 'capistrano/symfony'

# Load custom tasks from `lib/capistrano/tasks` if you have any defined
Dir.glob('app/config/deploy/tasks/*.rake').each { |r| import r }
