# server-based syntax
# ======================
# Defines a single server with a list of roles and multiple properties.
# You can define all roles on a single server, or split them:

# server 'example.com', user: 'deploy', roles: %w{app db web}, my_property: :my_value
# server 'example.com', user: 'deploy', roles: %w{app web}, other_property: :other_value
# server 'db.example.com', user: 'deploy', roles: %w{db}
server 'web01.chalet.nl', user: 'chalet01', roles: %w{app, web}
server 'web02.chalet.nl', user: 'chalet01', roles: %w{app, web}

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, -> { '/var/www/new-prod.chalet.nl' }

# because of a 'bug' in sshkit, we need to define this in each stage file
SSHKit.config.command_map[:composer] = "COMPOSER_CACHE_DIR=#{fetch(:tmp_dir)}/.composer php #{shared_path.join('composer.phar')}"

# set environment
set :symfony_env, 'prod'

# clearing files not applicable to environment
set :controllers_to_clear, ["app_dev.php", "app_stag.php", "htaccess.dev.dist", "htaccess.stag.dist"]

# only deploy master branch to production
set :branch, 'master'

print "\n"
print "=====>>>>> Deploy to production servers web01 and web02 <<<<<=====\n"
print "\n"

if fetch(:current_branch) != fetch(:branch)
    print "Set branch to " + fetch(:branch) + " to deploy!"
    print "\n"
    print "\n"
    exit 1
end

printf "Deploy to production? Press 'y' to continue: "
prompt = STDIN.gets.chomp
exit unless prompt == 'y'


# role-based syntax
# ==================

# Defines a role with one or multiple servers. The primary server in each
# group is considered to be the first unless any  hosts have the primary
# property set. Specify the username and a domain or IP for the server.
# Don't use `:all`, it's a meta role.

# role :app, %w{deploy@example.com}, my_property: :my_value
# role :web, %w{user1@primary.com user2@additional.com}, other_property: :other_value
# role :db,  %w{deploy@example.com}



# Configuration
# =============
# You can set any configuration variable like in config/deploy.rb
# These variables are then only loaded and set in this stage.
# For available Capistrano configuration variables see the documentation page.
# http://capistranorb.com/documentation/getting-started/configuration/
# Feel free to add new variables to customise your setup.



# Custom SSH Options
# ==================
# You may pass any option but keep in mind that net/ssh understands a
# limited set of options, consult the Net::SSH documentation.
# http://net-ssh.github.io/net-ssh/classes/Net/SSH.html#method-c-start
#
# Global options
# --------------
#  set :ssh_options, {
#    keys: %w(/home/rlisowski/.ssh/id_rsa),
#    forward_agent: false,
#    auth_methods: %w(password)
#  }
#
# The server-based syntax can be used to override options:
# ------------------------------------
# server 'example.com',
#   user: 'user_name',
#   roles: %w{web app},
#   ssh_options: {
#     user: 'user_name', # overrides user setting above
#     keys: %w(/home/user_name/.ssh/id_rsa),
#     forward_agent: false,
#     auth_methods: %w(publickey password)
#     # password: 'please use keys'
#   }
