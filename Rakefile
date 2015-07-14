task :console, [:cmd, :args] do |t, args|
  args.with_defaults(args: nil)
  sh "php app/console #{args.cmd}" + (args.args != nil ? " #{args.args}" : '')
end

namespace :deploy do
  
  task :all, [:user, :group, :env, :debug] do |t, args|
    args.with_defaults(debug: false)
    
    # clearing cache
    Rake::Task['deploy:cache'].invoke(args.env, args.debug)
    
    # clearing assets
    Rake::Task['deploy:assets:clear'].invoke(args.env, args.debug)
    
    # recreating assets
    Rake::Task['deploy:assets:dump'].invoke(args.env, args.debug)
    
    # setting ownership back
    Rake::Task['deploy:ownership'].invoke(args.user, args.group)
  end
  
  task :cache, [:env, :debug] do |t, args|
    args.with_defaults(debug: false)
    debug = args.debug ? ' --no-debug' : ''   
      
    Rake::Task[:console].invoke('cache:clear', "--env=#{args.env}#{debug}")
  end
  
  namespace :assets do
    task :clear do
    end
    
    task :dump, [:env, :debug] do |t, args|
      args.with_defaults(debug: false)
      debug = args.debug ? ' --no-debug' : ''
      
      Rake::Task[:console].invoke('assetic:dump', "--env=#{args.env}#{debug}")
    end
  end
  
  task :ownership, [:user, :group] do |t, args|
    sh "sudo chown -R #{args.user}:#{args.group} app/cache app/logs"
  end
  
  task :routes do
    Rake::Task[:console].invoke('fos:js-routing:dump')
  end
end