#!/usr/bin/env ruby
# encoding: utf-8

require 'pathname' #ruby 1.8 does not know about File.realpath()
realpath = Pathname.new(Pathname.new(__FILE__).dirname).realpath.to_s

#settings
$settings = {
### Public Server
  "webbasedir"=>"http://burda.cdnetworks.net/burda/FLO/Patch/Update",
  "clientbasedir"=>"#{realpath}/tmp/server_unpacked/burda.cdnetworks.net/burda/FLO/Patch/Update",
### Testserver
#  "webbasedir"=>"http://flopatch.florensia.eu/bananasplit/Patch/Update",
#  "clientbasedir"=>"#{realpath}/tmp/server_unpacked/flopatch.florensia.eu/bananasplit/Patch/Update",
### Public Server (jFlo)
#  "webbasedir"=>"http://122.199.241.50/RealServer/Update/",
#  "clientbasedir"=>"#{realpath}/tmp/server_unpacked/122.199.241.50/RealServer/Update/",
### Test Server (jFlo)
#  "webbasedir"=>"http://122.199.241.50/TestServer/Update/",
#  "clientbasedir"=>"#{realpath}/tmp/server_unpacked/122.199.241.50/TestServer/Update/",
  "basedir"=>"#{realpath}",
  "workingdir"=>"#{realpath}/tmp",
  "flobaselanguages"=>[],
  "fileserverextensions"=>["png", "bmp", "gif"],
  "serverimages" => "/home/florensiabase/public_html/images",
  "fileextensions"=>["png", "bmp", "gif", "xml", "bin", "in_", "ini", "dat", "txt"],
  "console"=>true,
  "debug"=>true,
  "downloadthreads"=>10,
  "unpackimages"=>"/media/programming/web/florensia/images",
  "serverlockfile" => "/home/florensiabase/public_html/lock",
  "ssh" => {
    "host" => "xxxxxxx",
    "user" => "xxxxxxx",
    "port" => 22,
    "password" => "xxxxxxx"
  },
  'mysql' => {
    "server" => {
      "user" => "xxxxxxx",
      "password" => "xxxxxxx",
      "database" => "florensia",
      "exportdir" => "/home/florensiabase",
      "exportfile" => "mysqldump.sql.tar.gz",
      "socket" => "/var/run/mysqld/mysqld.sock", #unused
      "port" => 3306, #unused
      "ignore_tables"=> [
          "flobase_character",
          "flobase_character_archiv",
          "flobase_character_data",
          "flobase_character_log_guild",
          "flobase_character_log_level_land",
          "flobase_character_log_level_sea",
          "flobase_guild_log_general"
          ]
    },
    "local" => {
      "host" => "localhost",
      "user" => "xxxxxxx",
      "database" => "florensia",
      "password" => "xxxxxxx",
      "socket" => "/var/run/mysqld/mysqld.sock",
      "port" => 3306
    }
  },
  "clientlanguage"=>[],
  "npcfiles"=>["s_MonsterChar.txt", "s_CitizenChar.txt", "s_MerchantChar.txt", "s_GwChar.txt", "s_GuardChar.txt"],
  "columns"=>{
    "npc"=>{},
    "item"=>{},
    "class"=>{},
    "skill"=>{},
    "misc"=>{}
  },
  "additionalfiles"=> {
    "server_100floor"=>"s_floorDungeonData.txt",
    "server_upgraderule"=>"s_UpgradeRule.txt",
    "server_exptable_land"=>"s_LandExpTable.txt",
    "server_exptable_sea"=>"s_SeaExpTable.txt"
  }
}
#end of configuration
require 'rubygems'
require 'mysql'
require 'open-uri'
require 'htmlentities'
require "rexml/document"
require "fileutils"
#require "iconv"
require 'net/ssh'

if ARGV.include?("--help") then
  puts "Possible options are:"
  puts "  --help                    Print this helpfile"
  puts "  --oldworkingdir           Use the old working directory. No new backup will be created and no unpack will happen unless --unpack is specified."
  puts "  --unpack                  Force unpack-routine of files and images"
  puts "  --nodownload              Do not start any download."
  puts "  --nosqldump               Do not create a database dump of the online server"
  puts "  --nosqlbackup             Do not create a local backup database."
  puts "  --noserverimagedownload   Images on the server won't be updated."
  exit
end

$tmp = {
  'threads'=>[],
  'wgetfile'=>[],
  'wgetfileserver'=>nil
}
$ssh = nil
File.umask(0)

def log (t="")
  filewrite = File.new($settings['basedir']+"/autoupdate.logfile."+Time.now.strftime("%Y.%m"), File::CREAT|File::APPEND|File::WRONLY, 0777)
  puts Time.now.strftime("[%d.%m.%Y :: %H:%M:%S] | ")+t if ($settings['console'])
  filewrite.write(Time.now.strftime("[%d.%m.%Y :: %H:%M:%S] | ")+t+"\n")
  filewrite.close
end

if !File.directory?($settings['workingdir']) && ARGV.include?("--oldworkingdir") then
  log "You may use --oldworkingdir only if this directory really exists."
  log "Make sure you specified the correct working directory:"
  log $settings['workingdir']
  exit
end


##################
# trims all varchar-columns of $table
##################
def trimsql(table, columns=[])
  log "Trimming columns of #{table}..."
  onworking = []
  $sql.query("SHOW COLUMNS FROM #{table}").each do |c|
    next if !/^varchar/i.match(c[1]) || (!columns.empty? && !columns.include?(c[0]))
    onworking << [c[0], c[1]]
  end
  onworking.each do |cname, ctype|
#    log "  Trimming #{cname}..."
    /\(([0-9]+)\)/.match(ctype)
    size = $1.to_i #save old size
    begin
      tmp = $sql.query("SELECT MAX(CHARACTER_LENGTH(#{cname})) FROM `#{table}`").fetch_row[0]
      size = tmp.to_i
    rescue
    end
    begin
      size = 1 if size <= 0
      $sql.query("ALTER TABLE `#{table}` CHANGE `#{cname}` `#{cname}` VARCHAR(#{size}) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL")
    rescue Mysql::WrongKeyColumn
      log "Error: Cannot trim #{cname} to VARCHAR(#{size})."
    end
  end
  log "  ... Done."
end


##################
# execute the given command on the ssh connection
##################
def ssh_exec(cmd, *args)
      if !$ssh then
        log "Error: No SSH process found."
        my_exit
      end
      
      log "  ssh: #{cmd}"
      log "       Executing in quiet mode." if args.include?("quiet")
      t = Time.now
      channel = $ssh.open_channel do |ch|
        ch.exec cmd do |ch, success|
          raise "could not execute command" unless success
  
          # "on_data" is called when the process writes something to stdout
          ch.on_data do |c, data|
            log "  "+data.chomp.strip unless args.include?(":quiet")
          end
  
          # "on_extended_data" is called when the process writes something to stderr
          ch.on_extended_data do |c, type, data|
            log "! "+data.chomp.strip  unless args.include?(":quiet")
          end
  
          ch.on_close { log "  ... Done. (#{(Time.now-t).to_f}s)" }
        end
      end
      channel.wait
end


##################
# hook to default exec-command but print a logline
##################
def my_exec(cmd)
  #log "  exec: #{cmd}"
  exec cmd
end


##################
# reading files into db
##################
def file2sql(table, files, add={})
  settings = {
      'defaulttype'=>"varchar(255) NOT NULL",
      'additionalcolumns'=>[],
      'column'=>{},
      'key'=>[],
      'fulltext'=>[],
      'columnprefix'=>"",
      'columnsuffix'=>"",
      'fterminated'=>"\t"
  }
  settings = settings.merge(add)
  
  #everytime drop the table
  $sql.query("DROP TABLE IF EXISTS `#{table}`")
  headerexist = []
  
  files.each do |doc|
    header =  doc.shift.split(settings['fterminated']) #read the header
    header.collect! { |h| settings['columnprefix']+h+settings['columnsuffix'] }
    
    if headerexist.empty? then
      #need to create table first
      dbcreate = []
      #"normal" columns
      settings['additionalcolumns'].each do |h|
        /^`([^`]+)`/.match(h)
        headerexist << $1
        dbcreate << h
      end
      header.each do |h|
        headerexist << h
        if settings['column'].has_key?(h) then
          dbcreate << "`#{h}` #{settings['column'][h]}"
        else
          dbcreate << "`#{h}` #{settings['defaulttype']}"
        end
      end
      
      #primary
      dbcreate << "PRIMARY KEY (`#{settings['primary']}`)"
      #keys
      settings['key'].each do |k|
        dbcreate << "KEY `#{k}` (`#{k}`)" if headerexist.include?(k)
      end
      #fulltext
      settings['fulltext'].each do |k|
        dbcreate << "FULLTEXT KEY `#{k}` (`#{k}`)" if headerexist.include?(k)
      end
      dbcreate.collect!{ |v| v.force_encoding("UTF-8").to_s }
      begin
        $sql.query("CREATE TABLE IF NOT EXISTS `#{table}` (
              #{dbcreate.join(",\n")}
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8")
        log "  Successfully automatically created table #{table}"
      rescue
        log "Error: Cannot auto-create table #{table}: #{$!}"
        "CREATE TABLE IF NOT EXISTS `#{table}` (
              #{dbcreate.join(",\n")}
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8".split("\n").each do |l|
              log l
            end
        exit
      end
    else
      #make sure we have all columns we need and create if someone is left
      dbcreate = []
      newheader = header-headerexist
      newheader.each do |h|
        #found header to add
        headerexist << h
        if settings['column'].has_key?(h) then
          dbcreate << "`#{h}` #{settings['column'][h]}"
        else
          dbcreate << "`#{h}` #{settings['defaulttype']}"
        end
        #add keys?
        dbcreate << "KEY `#{h}` (`#{h}`)" if settings['key'].include?(h)
        dbcreate << "FULLTEXT KEY `#{h}` (`#{h}`)" if settings['fulltext'].include?(h)       
      end
      if !dbcreate.empty? then
        begin
          $sql.query("ALTER TABLE `#{table}` ADD #{dbcreate.join(",\n")}")
          log "  Successfully added new column(s) \"#{newheader.join(", ")}\" to auto-created table #{table}"
        rescue
          log "Error: Cannot add column(s) \"#{newheader.join(", ")}\" to auto-created table #{table}: #{$!}"
          "ALTER TABLE `#{table}` ADD #{dbcreate.join(",\n")}".split("\n").each do |l|
                log l
          end
          exit
        end
      end
    end
    #save doc as file and load it into the BD
    #delete old tmp file if we run previously into an error
    File.delete($settings['workingdir']+"/dbautocreate.tmp") if File.exists?($settings['workingdir']+"/dbautocreate.tmp")
    tmp = File.new($settings['workingdir']+"/dbautocreate.tmp", File::CREAT|File::APPEND|File::WRONLY, 0777)
    tmp.write(doc.join("\n"))
    tmp.close

    $sql.query("LOAD DATA INFILE '#{$settings['workingdir']+"/dbautocreate.tmp"}' IGNORE INTO TABLE #{table} CHARACTER SET 'utf8' FIELDS TERMINATED BY '#{settings['fterminated']}' (#{header.join(", ")})")
    File.delete($settings['workingdir']+"/dbautocreate.tmp")
  end
end


##################
# get the korean column name
##################

def columnname(code_english, table)
  return nil unless $settings['columns'].has_key?(table)
  $settings['columns'][table][code_english].force_encoding("UTF-8").to_s
end


##################
# Some copy/move of old directories
##################

unless ARGV.include?("--oldworkingdir") then
  if File.directory?($settings['workingdir']) then
    if File.directory?($settings['workingdir']+"_old") then
      log "Found old backup directory. Deleting..."
      fork do
        my_exec "rm -r #{$settings['workingdir']}_old"
      end
      Process.wait
      log "  ... Done."
    end
    log "Backup old working directory... (Adding suffix \"*_old\")"
    fork do
      my_exec "mv #{$settings['workingdir']} #{$settings['workingdir']}_old"
    end
    Process.wait
    log "  ... Done."
  else
    log "Old working directory was not found. Nothing to delete."
  end
  Dir.mkdir($settings['workingdir'], 0777)
  Dir.mkdir($settings['workingdir']+"/server")
  log "New working directory successfully created."
else
  log "Using old directories (--oldworkingdir)"
end

##################
# Get version.bin, create wget-file(s) and start downloading
##################

unless ARGV.include?("--nodownload") then
  log "Getting version.bin..."
  File.delete($settings['workingdir']+"/version.bin") if File.exists?($settings['workingdir']+"/version.bin")
  begin
    versionfile = open($settings['webbasedir']+"/version.bin")
    #saving to working dir
    filewrite = File.new($settings['workingdir']+"/version.bin", File::CREAT|File::APPEND|File::WRONLY, 0777)
    filewrite.write(versionfile.read)
    filewrite.close
    versionfile.rewind #set pointer to the beginning
  rescue
    log "  Error while getting version.bin on #{$settings['webbasedir']}: #{$!}"
    exit
  end
  log "  ... Done."
  
  log "Writing selfmade update file..."
  log "  Searching for (local): #{$settings['fileextensions'].join(", ")}."
  log "  Searching for (server): #{$settings['fileserverextensions'].join(", ")}."
  Dir.glob($settings['workingdir']+"/wgetfile.*").each do |f|
      File.delete(f)
  end
  #local wget files
  $settings['downloadthreads'].times do |i|
    $tmp['wgetfile'][i] = File.new($settings['workingdir']+"/wgetfile.#{i}.txt", File::CREAT|File::APPEND|File::WRONLY, 0777)
  end
  #server wget file
  $tmp['wgetfileserver'] = File.new($settings['workingdir']+"/wgetfile.server.txt", File::CREAT|File::APPEND|File::WRONLY, 0777)
    
  $tmp['wgetfileamount'] = 0
  $tmp['wgetfileserveramount'] = 0
  while l = versionfile.gets do
    /^([^|]+)\.([a-z_]+)|/i.match(l.chomp.strip)
    if $settings['fileextensions'].include?($2) then
      $tmp['wgetfile'][$tmp['wgetfileamount']%$settings['downloadthreads']].write($settings['webbasedir']+"/"+$1+"."+$2+".zip\n")
      $tmp['wgetfileamount'] += 1
    end
    if $settings['fileserverextensions'].include?($2) then
      $tmp['wgetfileserver'].write($settings['webbasedir']+"/"+$1+"."+$2+".zip\n")
      $tmp['wgetfileserveramount'] += 1
    end
  end
  #closing local wget files
  $settings['downloadthreads'].times do |i|
    $tmp['wgetfile'][i].close
  end
  #closing server wget file
  $tmp['wgetfileserver'].close
  
  log "  ... Done. Needs to get #{$tmp['wgetfileamount']} files. Server needs to get #{$tmp['wgetfileserveramount']} files."

  log "Forking #{$settings['downloadthreads']} download threads..."
  forked = {}
  $settings['downloadthreads'].times do |i|
    pid = fork
    if !pid then
      Dir.chdir($settings['workingdir']+"/server")
      my_exec "wget -xNi #{$settings['workingdir']}/wgetfile.#{i}.txt -a #{$settings['workingdir']}/wgetfile.#{i}.log"
    else
      forked[pid] = i+1
    end
  end
  log "  All threads started. Waiting..."
  while !forked.empty? do
   pid, status = Process.wait2
   log "    Process DL:#{forked[pid]} returned: #{status}, #{forked.length-1} threads left."
   forked.delete(pid)
  end
  log "  ... Done."
else
  log "No download started. (--nodownload)"
end


##################
# Unpack downloaded files
##################
if (!ARGV.include?("--oldworkingdir") || ARGV.include?("--unpack")) then
  log "Unpacking all files... (#{$settings['workingdir']}/server_unpacked)"
  pid = fork
  if !pid then
    Dir.chdir($settings['workingdir']+"/server")
    my_exec "find -iname \"*.zip\" -exec #{$settings['basedir']}/autoupdate_unpack.sh \"{}\" #{$settings['workingdir']}/server_unpacked \\; > #{$settings['workingdir']}/unpack.log"
  end
  Process.wait(pid)
  log "  Process returned: #{$?}"
  log "  ... Done."
  
  log "Unpacking images twice... (#{$settings['unpackimages']})"
  ['bmp', 'png', 'gif'].each do |img|
    log "  Starting #{img}..."
    pid = fork
    if !pid then
      Dir.chdir($settings['workingdir']+"/server")
      my_exec "find -iname \"*.#{img}.zip\" -exec #{$settings['basedir']}/autoupdate_unpack_images.sh \"{}\" #{$settings['unpackimages']} \\; > #{$settings['workingdir']}/unpack.images.log" 
    end
    Process.wait(pid)
    log "  Process returned: #{$?}"
  end
  log "  ... Done."
else
  log "No unpack started. (--oldworkingdir)"
end

if !File.directory?($settings['clientbasedir'])
  log "Error: No such directory: #{$settings['clientbasedir']}"
  log "Error: Maybe client base directory has changed?"
  exit
end


##################
# Look up which *.bin files need to be updated, save them to .../bin
##################

log "Extracting *.bin files..."
Dir.mkdir($settings['workingdir']+"/bin") if !File.directory?($settings['workingdir']+"/bin")
$tmp['oldbasedir'] = $settings['clientbasedir'].sub($settings['workingdir'], $settings['workingdir']+"_old")
binfiles = []
i = 0
Dir.glob($settings['clientbasedir']+"/Data/DataTable/{ClientTable,ServerTable}/{s,c}_*.bin") { |bfile|
  /(ServerTable|ClientTable)\/([sc]_[a-z]+)\.bin$/i.match(bfile)
  next if $2 == "s_ExpTable"
  if (!File.directory?($tmp['oldbasedir']) || !File.exists?($tmp['oldbasedir']+"/Data/DataTable/#{$1}/#{$2}.txt") || File.mtime($tmp['oldbasedir']+"/Data/DataTable/#{$1}/#{$2}.bin") < File.mtime($settings['clientbasedir']+"/Data/DataTable/#{$1}/#{$2}.bin")) then
      log "  File changed: #{$1}/#{$2}.bin"
      i += 1
  end
  binfiles << bfile 
}
log "  Calling bin2txt (#{binfiles.length} files, #{i} of them changed since last update)..."
  pid = fork
  if !pid then
    my_exec "#{$settings['basedir']}/bin2txt.rb #{binfiles.join(" ")} > #{$settings['workingdir']}/unpack.bin.log" 
  end
  Process.wait(pid)
  log "    Process returned: #{$?}"
  log "  ... Done."


log "We are ready now. Continue? [y,n]"
while l = $stdin.gets do
  case l
  when /^(yes|y)/i
    break
  when /^(no|n)/i
    log "Breaking up!"
    exit
  end
end

##################
# download images to server
##################
unless ARGV.include?("--noserverimagedownload") then
    log "Download images to server..."
    $ssh = Net::SSH.start($settings['ssh']['host'], $settings['ssh']['user'], :password => $settings['ssh']['password'], :port => $settings['ssh']['port'])

    log "  Create update directory... (#{$settings['serverimages']}/update)"
    ssh_exec("test -d \"#{$settings['serverimages']}/update\" && rm -r \"#{$settings['serverimages']}/update\"")
    ssh_exec("mkdir \"#{$settings['serverimages']}/update\"")

    log "  Uploading wget-file..."
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "scp -P #{$settings['ssh']['port']} #{$settings['workingdir']}/wgetfile.server.txt #{$settings['ssh']['user']}@#{$settings['ssh']['host']}:#{$settings['serverimages']}/update/wget.txt"
    end
    
    Process.wait(pid)
    log "    Process returned: #{$?}"

    log "  Execute download of images (server)..."
    ssh_exec("cd \"#{$settings['serverimages']}/update\" && wget -nv -Ni wget.txt")
    ssh_exec("cd \"#{$settings['serverimages']}/update\" && unzip -LL -ou \"*.zip\" -d \"#{$settings['serverimages']}\"", :quiet)
    
    log "  ... Done."
    $ssh.close
else
  log "No download of images to the server started. (--noserverimagedownload)"
end

##################
# Dump the online-database
##################

unless ARGV.include?("--nosqldump") then
    $ssh = Net::SSH.start($settings['ssh']['host'], $settings['ssh']['user'], :password => $settings['ssh']['password'], :port => $settings['ssh']['port'])
    log "Look up if we need to delete an old dumpfile."
    ssh_exec("test -f \"#{$settings['mysql']['server']['exportdir']}/#{$settings['mysql']['server']['exportfile']}\" && rm \"#{$settings['mysql']['server']['exportdir']}/#{$settings['mysql']['server']['exportfile']}\"")
    
    log "Locking server..."
    ssh_exec("test -f \"#{$settings['serverlockfile']}\" || touch \"#{$settings['serverlockfile']}\"")
    
    log "Waiting 20 seconds so all sql-processes are able to finish..."
    sleep(20)
    
    log "Dump server database... (#{$settings['mysql']['server']['database']})"
    ssh_exec("mysqldump #{$settings['mysql']['server']['database']} --user=#{$settings['mysql']['server']['user']} --password=#{$settings['mysql']['server']['password']} #{$settings['mysql']['server']['ignore_tables'].collect{|i_table| "--ignore-table=#{$settings['mysql']['server']['database']}.#{i_table}"}.join(" ")} --single-transaction | gzip > #{$settings['mysql']['server']['exportdir']}/#{$settings['mysql']['server']['exportfile']}")
    log "  ... Done."
    
    $ssh.close
    
    
    log "Loading compressed package..."
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "scp -P #{$settings['ssh']['port']} #{$settings['ssh']['user']}@#{$settings['ssh']['host']}:#{$settings['mysql']['server']['exportdir']}/#{$settings['mysql']['server']['exportfile']} #{$settings['mysql']['server']['exportfile']}"
    end
    
    Process.wait(pid)
    log "  Process returned: #{$?} (#{File.size($settings['workingdir']+"/"+$settings['mysql']['server']['exportfile']).to_f/1024/1024} MB)"
    
    
    log "Loading into database..."
    t = Time.now
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "gunzip < #{$settings['mysql']['server']['exportfile']} | mysql --user=#{$settings['mysql']['local']['user']} --password=#{$settings['mysql']['local']['password']} --socket=#{$settings['mysql']['local']['socket']} #{$settings['mysql']['local']['database']}"
    end
    Process.wait(pid)
    log "  Process returned: #{$?} (#{(Time.now-t).to_f}s)"
else
   log "No sql-dump started. (--nosqldump)"
end


##################
# Create backup of local database
##################

unless ARGV.include?("--nosqlbackup") then
    backupdatabasename = $settings['mysql']['local']['database']+"_"+Time.now.to_i.to_s
    
    log "Export local database..."
    t = Time.now
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "mysqldump #{$settings['mysql']['local']['database']} --user=#{$settings['mysql']['local']['user']} --password=#{$settings['mysql']['local']['password']} --socket=#{$settings['mysql']['local']['socket']} | gzip > tmp.tar.gz"
    end
    Process.wait(pid)
    log "  Process returned: #{$?} (#{(Time.now-t).to_f}s)"
    
    
    log "Creating new local backup database... (#{backupdatabasename})"
    t = Time.now
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "mysql -C --user=#{$settings['mysql']['local']['user']} --password=#{$settings['mysql']['local']['password']} --socket=#{$settings['mysql']['local']['socket']} --execute=\"CREATE DATABASE #{backupdatabasename}\""
    end
    Process.wait(pid)
    log "  Process returned: #{$?} (#{(Time.now-t).to_f}s)"
    
    
    log "Import backup into backup-database..."
    t = Time.now
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "gunzip < tmp.tar.gz | mysql -C --user=#{$settings['mysql']['local']['user']} --password=#{$settings['mysql']['local']['password']} --socket=#{$settings['mysql']['local']['socket']} #{backupdatabasename}"
    end
    Process.wait(pid)
    log "  Process returned: #{$?} (#{(Time.now-t).to_f}s)"
    
    
    log "Truncate flobase_character table of old backup database..."
    t = Time.now
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "mysql -C --user=#{$settings['mysql']['local']['user']} --password=#{$settings['mysql']['local']['password']} --socket=#{$settings['mysql']['local']['socket']} --execute=\"TRUNCATE TABLE flobase_character\" #{backupdatabasename}"
    end
    Process.wait(pid)
    log "  Process returned: #{$?} (#{(Time.now-t).to_f}s)"
    
    log "Truncate flobase_character_data table of old backup database..."
    t = Time.now
    pid = fork
    if !pid then
        Dir.chdir($settings['workingdir'])
        my_exec "mysql -C --user=#{$settings['mysql']['local']['user']} --password=#{$settings['mysql']['local']['password']} --socket=#{$settings['mysql']['local']['socket']} --execute=\"TRUNCATE TABLE flobase_character_data\" #{backupdatabasename}"
    end
    Process.wait(pid)
    log "  Process returned: #{$?} (#{(Time.now-t).to_f}s)"
else
   log "No sql-backup created. (--nosqlbackup)"
end


log "All prework finished. Start updating."

##################
# starting... connect to DB
##################

log "Connecting to MySQL on #{$settings['mysql']['local']['host']}..."
begin
  $sql = Mysql.connect($settings['mysql']['local']['host'], $settings['mysql']['local']['user'], $settings['mysql']['local']['password'], $settings['mysql']['local']['database'], $settings['mysql']['local']['port'], $settings['mysql']['local']['socket'])
  $sql.query("SET NAMES 'utf8'")
  $sql.charset = 'utf8' if ($sql.respond_to?('charset'))
  rescue
  log "Error: Cannot open MySQL connection: #{$!}"
  exit
end

##################
# Get available FloBase languages
##################

log "Loading available languages on FloBase and save them to $settings['flobaselanguages']..."
$sql.query("SELECT languageid FROM flobase_language").each do |l|
  $settings['flobaselanguages'] << l[0]
end
log "  Languages are: #{$settings['flobaselanguages'].join(", ")}"
log "  ... Done."

##################
# Get item column names
##################
log "Loading available item column-names and save them to $settings['columns']['item']..."
q = $sql.query("SELECT code_korean, code_english FROM flobase_item_columns")
while (valid = q.fetch_row) do
  $settings['columns']['item'][valid[1]] = valid[0]
end
log "  ... Done. Found #{$settings['columns']['item'].length} entries."

##################
# Get npc column names
##################
log "Loading available npc column-names and save them to $settings['columns']['npc']..."
q = $sql.query("SELECT code_korean, code_english FROM flobase_npc_columns")
while (valid = q.fetch_row) do
  $settings['columns']['npc'][valid[1]] = valid[0]
end
log "  ... Done. Found #{$settings['columns']['npc'].length} entries."

##################
# Get class column names
##################
log "Loading available class column-names and save them to $settings['columns']['class']..."
q = $sql.query("SELECT code_korean, code_english FROM flobase_class_columns")
while (valid = q.fetch_row) do
  $settings['columns']['class'][valid[1]] = valid[0]
end
log "  ... Done. Found #{$settings['columns']['class'].length} entries."

##################
# Get skill column names
##################
log "Loading available skill column-names and save them to $settings['columns']['skill']..."
q = $sql.query("SELECT code_korean, code_english FROM flobase_skill_columns")
while (valid = q.fetch_row) do
  $settings['columns']['skill'][valid[1]] = valid[0]
end
log "  ... Done. Found #{$settings['columns']['skill'].length} entries."

##################
# Get misc column names
##################
log "Loading available misc column-names and save them to $settings['columns']['misc']..."
q = $sql.query("SELECT code_korean, code_english FROM flobase_misc_columns")
while (valid = q.fetch_row) do
  $settings['columns']['misc'][valid[1]] = valid[0]
end
log "  ... Done. Found #{$settings['columns']['misc'].length} entries."

##################
# Reading mapfile
##################
log "Reading mapfile... (.../Data/Map/MiniMapFactorial.xml)"
if !File.exists?($settings['clientbasedir']+"/Data/Map/MiniMapFactorial.xml") then
  log "Error: MiniMapFactorial.xml does not exist! Nothing done." 
else
  begin
    error = true
    doc = REXML::Document.new File.open($settings['clientbasedir']+"/Data/Map/MiniMapFactorial.xml")
    error = false
  rescue
    log "Error: Cannot open MiniMapFactorial.xml: #{$!}"
  end
  if !error then
    #recursively scan mapfile
    def tmpscanmap(map, parent, picid, coord)
        if map.attributes['id'] then
          picid = map.attributes['id']
          path = map.attributes['id'].gsub('\\', '')
          mfile = $settings['clientbasedir']+"/Data/Map/map_#{path}/map_#{path}_MiniMap.ini"
          if File.exists?(mfile) then
            #log "Mapfile found: .../Data/Map/map_#{path}/map_#{path}_MiniMap.ini"
            coordfile = open(mfile)
            while l = coordfile.gets
              if /^LTWH=([-0-9,. ]+)/.match(l.chomp.strip) then
                coord = $1.gsub(' ', '')
                break
              end
            end
          else
            log "  Warning: Mapfile not found: .../Data/Map/map_#{path}/map_#{path}_MiniMap.ini"
          end
        end
        #log "  Map: AreaCode:#{map.attributes['AreaCode']} / Pic: #{picid} / Path: #{path} / Coord: #{coord} / Parent:#{parent}"
        begin
          $sql.query("INSERT INTO server_map (mapid, parentmap, mappicture, LTWH) VALUES('#{map.attributes['AreaCode']}', '#{parent}', '#{picid}', '#{coord}')") if map.attributes['AreaCode'].to_s.length>0
        rescue
          log "Error: SQL-Insert-Error while adding mapinfo: #{$!}"
        end
        
        #recursiv
        map.elements.each do |submap|
          tmpscanmap(submap, map.attributes['AreaCode'], picid, coord)
        end      
    end

    $sql.query("TRUNCATE TABLE server_map")
    tmpscanmap(doc, nil, nil, nil)
  end
end
log "  ... Done."


##################
# Reading store-data of NPCs
##################
log "Reading store-data of NPCs... (.../Data/DataTable/ServerTable/StoreData.xml)"
if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/StoreData.xml") then
  log "Error: StoreData.xml does not exist! Nothing done." 
else
  begin
    error = true
    #doc = File.open($settings['clientbasedir']+"/Data/DataTable/ServerTable/StoreData.xml") { |f| Hpricot(f) }	
    doc = REXML::Document.new File.open($settings['clientbasedir']+"/Data/DataTable/ServerTable/StoreData.xml")
    error = false
  rescue
    log "Error: Cannot open StoreData.xml: #{$!}"
  end
  if !error then
    $sql.query("TRUNCATE TABLE server_storelist")
    
    doc.elements['StoreList'].elements.each do |npc|
      next if npc.elements.to_a.length<1
      
      npcid = npc.attributes['NpcCode']
      npc.elements.each do |section|
        section.elements.each do |item|
          $sql.query("INSERT IGNORE INTO server_storelist (npcid, itemid) VALUES('#{npcid}', '#{item.attributes['ItemCode']}')")
        end
      end
    end
  end
  log " ... Done."
end

##################
# Clean up droplist, adding quests, questtexts etc.
##################

log "Cleaning up droplist, adding questitems, and quests themselfs..."
if !File.directory?($settings['clientbasedir']+"/Data/DataTable/QuestData") then
  log "Error: .../Data/DataTable/QuestData does not exist or is not a directory! Nothing done." 
else

  $sql.query("DELETE d, r FROM flobase_droplist as d LEFT JOIN flobase_droplist_ratings as r ON(d.dropid=r.dropid) WHERE d.thumpsup=0 AND d.thumpsdown>=0")
  log "  Deleted #{$sql.affected_rows} entries with 0/0+ rankings"
  
  $sql.query("TRUNCATE flobase_droplist_quest")
  log "  Resetted old droplist_quest";
  
  $sql.query("TRUNCATE TABLE server_questlist")
  log "  Resetted old server_questlist"
  
  $sql.query("TRUNCATE TABLE server_questreferences")
  log "  Resetted old server_questreferences"

  log "  Adding new quests..."  
  nextquests = []
  Dir.glob($settings['clientbasedir']+"/Data/DataTable/QuestData/*.xml").each { |qfile|
    qfiledir = File.dirname(qfile)
    questid = File.basename(qfile, ".xml").downcase
    next if  /^(StringData|QuestIndex|QuestListIndex)/i.match(questid)
    doc = REXML::Document.new File.open(qfile)
    
    #handling nextquest
    qprev = doc.elements['Quest'].elements['OccurTerm'].attributes['BeforeQuest'].downcase
    nextquests << [qprev, questid] if qprev.length>0
    
    #handling droplist and set also refs
    doc.elements['Quest'].elements['LootDesc'].elements.each do |loot|
      $sql.query("INSERT INTO flobase_droplist_quest (dropid, questid, droprate) SELECT dropid, '#{Mysql.escape_string(questid)}', '#{Mysql.escape_string(loot.attributes['Rate'])}' FROM flobase_droplist WHERE itemid='#{Mysql.escape_string(loot.attributes['ItemCode'])}' AND npcid='#{Mysql.escape_string(loot.attributes['MonsterCode'])}'")
      
      $sql.query("INSERT IGNORE INTO server_questreferences (refid, questid) VALUES('#{loot.attributes['MonsterCode']}', '#{questid}')")
      $sql.query("INSERT IGNORE INTO server_questreferences (refid, questid) VALUES('#{loot.attributes['ItemCode']}', '#{questid}')")
    end
    #handling workvalue of npcs/quests
    doc.elements['Quest'].elements['Mission'].elements.each do |work|
      next unless work.attributes['WorkValue'] && work.attributes['WorkValue'].length > 1
      $sql.query("INSERT IGNORE INTO server_questreferences (refid, questid) VALUES('#{work.attributes['WorkValue']}', '#{questid}')")
    end
    
    #handling questclasses
    questclasses = ""
    questclasses << "W" if doc.elements['Quest'].elements['OccurTerm'].attributes["Class0"] == "1"
    questclasses << "P" if doc.elements['Quest'].elements['OccurTerm'].attributes["Class1"] == "1"
    questclasses << "E" if doc.elements['Quest'].elements['OccurTerm'].attributes["Class2"] == "1"
    questclasses << "N" if doc.elements['Quest'].elements['OccurTerm'].attributes["Class3"] == "1"
    questclasses << "S" if doc.elements['Quest'].elements['OccurTerm'].attributes["Class4"] == "1"
    questclasses = "WPENS" if questclasses.empty?

    #insert quest itself
    output = String.new
      #temporaly ignore $stderr because rexml.write is depricated but don't know how to use Formatters :D
      #REXML::Element.write is deprecated.  See REXML::Formatters
      $stderr_backup = $stderr.dup
      $stderr.reopen("/dev/null", "w")
      #do it!
      doc.write(output, 0)
      #readding $stderr so we are sure to see any sql-errors in the next command ;)
      $stderr = $stderr_backup.dup
    $sql.query("INSERT INTO server_questlist (questlistid, questlistxml, questlevel, questclasses, questsourcenpc, questsourcemap, privquest) VALUES('#{questid}', '#{Mysql.escape_string(output)}', '#{doc.elements['Quest'].elements['OccurTerm'].attributes['Lv']}', '#{questclasses}', '#{doc.elements['Quest'].attributes['SourceObject']}', '#{doc.elements['Quest'].attributes['SourceArea']}', '#{qprev}')")
  }

  #handling nextquest save
  log "  Setting nextquest information to #{nextquests.length} quests"
  nextquests.each do |q|
    $sql.query("UPDATE server_questlist SET nextquest='#{q[1]}' WHERE questlistid='#{q[0]}'")
  end 
  
  #handling questtext/title
  log "  Reading quest-dialogues (.../Data/DataTable/QuestData/StringData_*.in_)"
  qworkaround = []
  Dir.glob($settings['clientbasedir']+"/Data/DataTable/QuestData/StringData_*.in_").each { |qfile|
    /([a-z]+)\.in_$/i.match(qfile)
    language = $1
    log "    Reading #{language}..."
    
    $sql.query("CREATE TABLE IF NOT EXISTS `server_questtext_#{language}` (
		`questlistid` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
		`questtextxml` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
		PRIMARY KEY  (`questlistid`)
		)")
    $sql.query("TRUNCATE TABLE server_questtext_#{language}")
    begin
      $sql.query("ALTER TABLE server_questlist ADD questtitle_#{language} TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;")
    rescue
    end
    qworkaround << language
    
    doc = File.open(qfile).read
    #doc = Iconv.iconv('utf-8', 'utf-16', doc)
    doc.encode!('utf-8', 'utf-16')
    questlist = {}
    qid = nil
    doc.split("\r\n").each do |l|
      case l
      when /^\[(.+)\]$/i
        qid = $1.downcase
        questlist[qid] = {'text'=>String.new, 'title'=>String.new}
      when /^Title=(.+)/i
        questlist[qid]['title'] = $1
      when /^[^=]+=.+/i
        questlist[qid]['text'] << l+"\n"
      end
    end
    questlist.each_pair do |qid, quest|
      $sql.query("INSERT INTO server_questtext_#{language} (questlistid, questtextxml) VALUES('#{qid}', '#{Mysql.escape_string(quest['text'])}')")
      $sql.query("UPDATE server_questlist SET questtitle_#{language}='#{Mysql.escape_string(quest['title'])}' WHERE questlistid='#{qid}'")
    end
  }
  
  log "  Running workaround for deleted quests/quests without titles"
    workarounddb = []
    qworkaround.each do |lang|
      workarounddb << "questtitle_#{lang}=''"
    end
    workarounddb = workarounddb.join(" AND ")
    
    $sql.query("DELETE FROM server_questlist WHERE #{workarounddb}")
    log "    Deleted #{$sql.affected_rows} quests without title"

    log "  Update questtype for sea-quests"
    $sql.query("UPDATE server_questlist SET questtype='s' WHERE questlistid LIKE 'sea%'")

  log "  ... Done."
end

##################
# Reading strings
##################

log "Reading names... (.../Data/DataTable/StringTable/*Str.dat)"
if !File.directory?($settings['clientbasedir']+"/Data/DataTable/StringTable") then
  log "Error: .../Data/DataTable/StringTable does not exist or is not a directory! Nothing done." 
else
  docs = []
  files = {}
  Dir.glob($settings['clientbasedir']+"/Data/DataTable/StringTable/*Str.dat").each do |sfile|
    sfiledir, sfilename = File.split(sfile)
    #workaround, because the following files are the same
    #.../Data/DataTable/StringTable/PetSkillStoneItemStr.dat"
    #.../Data/DataTable/StringTable/PetSkillActionStr.dat"
    #
    filesize = File.size(sfile)
    if files.has_key?(filesize) then
      log "Warning: Ignored #{sfilename} (same as #{files[filesize]} (#{filesize} bytes))"
      next
    end
    files[filesize] = sfilename
    ## end workaround
    
    #next if sfilename =~ /^(Keyword|PortraitScript)_/ || sfilename =~ /Res\.dat$/
    #log "  Loading #{sfilename}..."
    #get the first line which specify the columns
    doc = open(sfile).read
    #doc = Iconv.iconv('utf-8', 'utf-16', doc)
    doc.encode!('utf-8', 'utf-16')
    
    doc = doc.split("\r\n")
    doc.pop #always delete the last line __END
    
    docs << doc
  end
  s = {
#    'additionalcolumns'=>[
#      "`id` int(11) NOT NULL AUTO_INCREMENT"
#    ],
    'primary'=>'Code',
#    'key'=>['Code'],
    'fulltext'=>['Korea', 'Japan', 'English', 'German', 'Italian', 'Spanish', 'Portuguese', 'French', 'Turkish', 'China', 'Taiwan'],
  }
  file2sql('server_stringtable', docs, s)
  
  log "  ... Done. Added #{docs.length} files."
  trimsql("server_stringtable")
end
  log "Loading client languages into $settings['clientlanguage']..."
  $sql.query("SHOW COLUMNS FROM server_stringtable").each do |c, dummy|
    next if ["id", "Name", "Code"].include?(c)
    $settings['clientlanguage'] << c
  end
  log "  Languages are: #{$settings['clientlanguage'].join(", ")}"
  log "  ... Done."

##################
# Reading descriptions
##################

log "Reading descriptions... (.../Data/DataTable/StringTable/*Desc.dat)"
if !File.directory?($settings['clientbasedir']+"/Data/DataTable/StringTable") then
  log "Error: .../Data/DataTable/StringTable does not exist or is not a directory! Nothing done." 
else
  docs = []
  Dir.glob($settings['clientbasedir']+"/Data/DataTable/StringTable/*Desc.dat").each do |sfile|
    sfiledir, sfilename = File.split(sfile)
    #next if sfilename =~ /^(Keyword|PortraitScript)_/ || sfilename =~ /Res\.dat$/
    #log "  Loading #{sfilename}..."
    #get the first line which specify the columns
    doc = open(sfile).read
    #doc = Iconv.iconv('utf-8', 'utf-16', doc)
    doc.encode!('utf-8', 'utf-16')
    doc = doc.split("\r\n")
    doc.pop #always delete the last line __END
    docs << doc
  end
  s = {
#    'additionalcolumns'=>[
#      "`id` int(11) NOT NULL AUTO_INCREMENT"
#    ],
    'primary'=>'Code',
#    'key'=>['Code'],
    'fulltext'=>['Korea', 'Japan', 'English', 'German', 'Italian', 'Spanish', 'Portuguese', 'French', 'Turkish', 'China', 'Taiwan'],
  }
  file2sql('server_description', docs, s)
  
  log "  ... Done. Added #{docs.length} files."
  trimsql("server_description")
end


##################
# Reading NPC-data
##################
log "Loading NPCs... (.../Data/DataTable/ServerTable)"
if !File.directory?($settings['clientbasedir']+"/Data/DataTable/ServerTable") then
  log "Error: .../Data/DataTable/ServerTable does not exist or is not a directory! Nothing done." 
else
  docs = []
  $settings['npcfiles'].each do |f|
    log "  Reading #{f}..."
    if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/#{f}") then
      log "Error: File .../Data/DataTable/ServerTable/#{f} does not exist. Ignoring."
      next
    end
    /^s_([a-z]+)\.txt$/i.match(f)
    npc_file = $1
    
    doc = open($settings['clientbasedir']+"/Data/DataTable/ServerTable/#{f}").read
    doc = doc.split("\n")
    
    #split them on , and join new with \t also adding npc_file column and values
    doc.collect! { |l| l + "\t#{npc_file}" }
    doc[0] = doc[0].sub("\t#{npc_file}", "\tfile")
    docs << doc
  end
  
  #create additional language columns
  clist = []
  $settings['clientlanguage'].each do |c|
    clist << "`name_#{c}` varchar(250) NOT NULL"
  end
  npckey = docs[0][0].split("\t")[0]
  s = {
    'defaulttype'=>"varchar(150) NOT NULL",
    'additionalcolumns'=>[
      "`npc_picture` varchar(50) NOT NULL",
      "`npc_bossfactor` smallint(6) NOT NULL"
    ] + clist,
    'primary'=>columnname("npcid", "npc"),
    'key'=>['Code', columnname("level", "npc"), columnname("exp", "npc"), 'npc_file'],
    'columnprefix'=>'npc_',
    'column'=>{
#      "npc_기준레벨"=>'int(11) NOT NULL',
#      "npc_보상경험치"=>'int(11) NOT NULL',
      columnname("exp", "npc")=>'int(11) NOT NULL',
      columnname("level", "npc")=>'int(11) NOT NULL'
    },
    'fulltext'=>$settings['clientlanguage'].dup.collect!{ |c| "name_#{c}" }
  }

  file2sql('server_npc', docs, s)
  log "  ... Added #{docs.length} files."
  
  
  log "    Loading npc-names into the table..."
  $sql.query("SELECT npc_#{npckey} FROM server_npc").each do |npcid|
    names = $sql.query("SELECT #{$settings['clientlanguage'].join(",")} FROM server_stringtable WHERE Code='#{npcid[0]}' LIMIT 1").fetch_row
    next if !names
    db = []
    i = 0
    names.each do |n|
      db << "name_#{$settings['clientlanguage'][i]}='#{Mysql.escape_string(n)}'"
      i += 1
    end
    $sql.query("UPDATE server_npc SET #{db.collect{|v| v.force_encoding("UTF-8").to_s}.join(",")} WHERE npc_#{npckey}='#{npcid[0]}'")
  end
  log "    ... Done."
  
  log "    Adding missing columns to flobase_npc_columns and save them into $settings['columns']['npc']..."
  i = 0
  columns = $sql.query("SHOW COLUMNS FROM server_npc").each do |c|
    next if ['npc_picture', 'npc_bossfactor', 'npc_file'].include?(c[0]) || /^name_/.match(c[0])
    valid = $sql.query("SELECT code_english FROM flobase_npc_columns WHERE code_korean='#{Mysql.escape_string(c[0])}'").fetch_row
    if !valid then
      i += 1
      log "    Adding #{c[0]}..."
      $settings['columns']['npc'][c[0]] = c[0]
      $sql.query("INSERT INTO flobase_npc_columns (code_english, code_korean, name, #{$settings['flobaselanguages'].dup.collect!{|x| "name_"+x}.join(", ")}) VALUES(#{(["'"+Mysql.escape_string(c[0])+"'"]*(3+$settings['flobaselanguages'].length.to_i)).join(", ")})")
    end
  end  
  log "    ... Done. #{i} rows have been added."


  log "    Analyse bossfactor..."
  $sql.query("SELECT #{columnname("npcid", "npc")}, #{columnname("level", "npc")}, #{columnname("exp", "npc")} FROM server_npc").each do |npcid, level, exp|
    level = level.to_i
    exp = exp.to_i
    if level<1 then
      factor = 0
    else
      factor = (exp.to_f/level.to_f).round
    end
    $sql.query("UPDATE server_npc SET npc_bossfactor='#{factor}' WHERE #{columnname("npcid", "npc")}='#{npcid}'")
  end
  log "    ... Done."
  
  log "    Loading npc images..."
  $settings['npcfiles'].each do |f|
    f = f.gsub(/^s_(.+)\.txt$/i, 'c_\1Res.txt')
    log "      Reading #{f}..."
    if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ClientTable/#{f}") then
      log "Error: File .../Data/DataTable/ClientTable/#{f} does not exist. Ignoring."
      next
    end
    
    doc = open($settings['clientbasedir']+"/Data/DataTable/ClientTable/#{f}")
    header = doc.gets
    #always compare headers, if they change we have a problem and need to rework the columns...
    sheader = header if !sheader
    if sheader.eql?(header) then
      while l = doc.gets
        l.chomp.strip
        next if l.length<1
        l = l.split("\t")
        #npcid on 0, imageID on 2
        $sql.query("UPDATE server_npc SET npc_picture='#{l[2]}' WHERE #{columnname("npcid", "npc")}='#{l[0]}'")
      end
    else
      log "Error: Headers are changing! Need to rework the columns >.<"
      exit
    end
  end
  log "    ... Done."
  
  log "  ... Done."
  trimsql("server_npc")
end


##################
# Reading item-data
##################
log "Loading Items... (.../Data/DataTable/ServerTable)"
if !File.directory?($settings['clientbasedir']+"/Data/DataTable/ServerTable") then
  log "Error: .../Data/DataTable/ServerTable does not exist or is not a directory! Nothing done." 
else
  docs = []
  clist = []
  allitemtables = []
  $settings['clientlanguage'].each do |c|
    clist << "`name_#{c}` varchar(100) NOT NULL"
  end
  
  Dir.glob($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_*Item.txt").each do |f|
    filedir, filename = File.split(f)
    /^s_(.+)\.txt$/.match(filename)
    dbname = $1.downcase
    allitemtables << dbname
    log "  Reading #{filename}..."
    
    doc = open(f).read.split("\n")
    s = {
      'defaulttype'=>"varchar(100) NOT NULL",
      'additionalcolumns'=>clist,
      'primary'=>columnname("itemid", "item"),
      'fulltext'=>$settings['clientlanguage'].dup.collect!{ |c| "name_#{c}" },
      'columnprefix'=>"item_",
      'column'=>{
        columnname("lvlland", "item")=>"SMALLINT(6) NOT NULL DEFAULT '0'",
        columnname("lvlsea", "item")=>"SMALLINT(6) NOT NULL DEFAULT '0'"
      }
    }
    file2sql("server_item_#{dbname}", [doc], s)
    
    log "    Loading item-names into table..."
    $sql.query("SELECT #{columnname("itemid", "item")} FROM server_item_#{dbname}").each do |itemid|
      names = $sql.query("SELECT #{$settings['clientlanguage'].join(",")} FROM server_stringtable WHERE Code='#{itemid[0]}' LIMIT 1").fetch_row
      db = []
      if !names then
        $settings['clientlanguage'].each do |l|
          db << "name_#{l}='#{Mysql.escape_string(itemid[0])}'"
        end
      else
        i = 0
        names.each do |n|
          db << "name_#{$settings['clientlanguage'][i]}='#{Mysql.escape_string(n)}'"
          i += 1
        end
      end
      $sql.query("UPDATE server_item_#{dbname} SET #{db.collect{|v| v.force_encoding("UTF-8").to_s}.join(",")} WHERE #{columnname("itemid", "item")}='#{itemid[0]}'")
    end
    log "    ... Done."
  end
  
  log "  ... Done."
  allitemtables.each do |d|
    trimsql("server_item_#{d}")
  end
end


##################
# Look up if any new item columns were created
##################

  log "Adding missing columns to flobase_item_columns and save them into $settings['columns']['item']..."
  i = 0
  $sql.query("SHOW TABLES LIKE 'server_item_%'").each do |t|
    next if t[0]=="server_item_idtable"
    columns = $sql.query("SHOW COLUMNS FROM #{t[0]}").each do |c|
      next if /^name_/.match(c[0])
      valid = $sql.query("SELECT code_english FROM flobase_item_columns WHERE code_korean='#{Mysql.escape_string(c[0])}'").fetch_row
      if !valid then
        i += 1
        log "    Adding #{c[0]}..."
        $settings['columns']['item'][c[0]] = c[0]
        $sql.query("INSERT INTO flobase_item_columns (code_english, code_korean, name, #{$settings['flobaselanguages'].dup.collect!{|x| "name_"+x}.join(", ")}) VALUES(#{(["'"+Mysql.escape_string(c[0])+"'"]*(3+$settings['flobaselanguages'].length.to_i)).join(", ")})")
      end
    end
  end
  log "  ... Done. #{i} rows have been added."
  #
  # delete items without any names?.. mh.. now no-name is the ID of the item.. maybe we don't need to, do we?
  #


##################
# Creates server_item_idtable
##################

log "Creating server_item_idtable..."
  clist = []
    $settings['clientlanguage'].each do |c|
      clist << "`name_#{c}` varchar(100) NOT NULL"
    end
    
    s = {
      'defaulttype'=>"varchar(250) NOT NULL",
      'additionalcolumns'=>[
        "`itemid` varchar(50) NOT NULL",
        "`tableid` varchar(50) NOT NULL",
        "`itempicture` varchar(50) NOT NULL",
        "`characterpicture` varchar(50) NOT NULL",
        "`usedbyrecipe` TEXT NOT NULL",
        "`producedbyrecipe` TEXT NOT NULL",
        "`questreward` TEXT NOT NULL"
      ]+clist,
      'primary'=>"itemid",
      'fulltext'=>$settings['clientlanguage'].dup.collect!{ |c| "name_#{c}" }
    }
    file2sql("server_item_idtable", [[""]], s)
    
    log "  Loading data into it..."
    log "    Adding ItemIDs..."
    $sql.query("SHOW TABLES LIKE 'server_item_%'").each do |t|
      next if t[0]=="server_item_idtable" || t[0]=="server_item_effect"
      /^server_item_(.+)$/.match(t[0])
      tname = $1
      $sql.query("SELECT #{columnname('itemid', 'item')} FROM #{t[0]}").each do |itemid|
        begin
          $sql.query("INSERT INTO server_item_idtable (itemid, tableid) VALUES('#{Mysql.escape_string(itemid[0])}', '#{tname}')")
        rescue #Mysql::DupEntry
          #double key for null00000
        end
      end
    end
    
    log "    Loading item-names into table..."
    $sql.query("SELECT itemid FROM server_item_idtable").each do |itemid|
      names = $sql.query("SELECT #{$settings['clientlanguage'].join(",")} FROM server_stringtable WHERE Code='#{itemid[0]}' LIMIT 1").fetch_row
      db = []
      if !names then
        $settings['clientlanguage'].each do |l|
          db << "name_#{l}='#{Mysql.escape_string(itemid[0])}'"
        end
      else
        i = 0
        names.each do |n|
          db << "name_#{$settings['clientlanguage'][i]}='#{Mysql.escape_string(n)}'"
          i += 1
        end
      end
      $sql.query("UPDATE server_item_idtable SET #{db.join(",")} WHERE itemid='#{itemid[0]}'")
    end
    
    
    log "    Loading item-images..."
    if !File.directory?($settings['clientbasedir']+"/Data/DataTable/ClientTable") then
      log "Error: .../Data/DataTable/ClientTable does not exist or is not a directory! Nothing done." 
    else
      Dir.glob($settings['clientbasedir']+"/Data/DataTable/ClientTable/*").each do |f|
        filedir, filename = File.split(f)
        next unless /^c_(.+)ItemRes\.txt/i.match(filename)

        doc = open(f)
        header = doc.gets.chomp.split("\t")
        itemid = header.index(columnname("itemid", "item").gsub(/^item_(.+)$/i, '\1'))
        itempic = header.index(columnname("smallimage", "item").gsub(/^item_(.+)$/i, '\1'))
         
        charpic = header.index(columnname("characterimage", "item").gsub(/^item_(.+)$/i, '\1')) 
        
        while (charpic && itempic && l = doc.gets) do
          next if l.length<1
          l = l.chomp.split("\t")
          $sql.query("UPDATE server_item_idtable SET characterpicture='#{l[charpic]}', itempicture='#{l[itempic]}' WHERE itemid='#{l[itemid]}'")
        end
      end
    end
    
  
    log "    Loading data of recipes..."
    koreancolumns = []
    recipematerials = {}
    recipetarget = {}
    log "      Used by recipe..."
    $sql.query("SELECT code_korean FROM flobase_item_columns WHERE code_english='recipe_material'").each do |k|
      koreancolumns << k[0]
    end
    
    #usedbyrecipe
    $sql.query("SELECT #{columnname("itemid", "item")}, #{koreancolumns.collect{|v| v.force_encoding("UTF-8").to_s}.join(",")} FROM server_item_recipeitem").each do |itemid, *a|
      a.each do |c|
        break if c=="#"
        recipematerials[c] = [] if !recipematerials[c]
        recipematerials[c] << itemid
      end
    end
    
    recipematerials.each_pair do |itemrecipe, requirements|
      $sql.query("UPDATE server_item_idtable SET usedbyrecipe='#{requirements.join(",")}' WHERE itemid='#{itemrecipe}'")
    end
    
    #producedbyrecipe
    log "      Produced by recipe..."
    $sql.query("SELECT #{columnname("itemid", "item")}, #{columnname("recipe_target", "item")} FROM server_item_recipeitem").each do |itemid, target|
      recipetarget[target] = [] if !recipetarget[target]
      recipetarget[target] << itemid
    end

    recipetarget.each_pair do |target, recipes|
      $sql.query("UPDATE server_item_idtable SET producedbyrecipe='#{recipes.join(",")}' WHERE itemid='#{target}'")
    end
      
    #questreward
    log "      Questrewards..."
    rewards = {}
    $sql.query("SELECT questlistid, questlistxml FROM server_questlist WHERE questlistxml LIKE '%<SelectItems>%'").each do |questid, xml|
      xml = REXML::Document.new xml
      xml.elements.each("/Quest/RewardDesc/SelectItems/*") do |i|
        rewards[i.attributes['ItemCode']] = rewards.has_key?(i.attributes['ItemCode']) ? (rewards[i.attributes['ItemCode']].split(",") << "#{questid}-#{i.attributes['Amount']}").join(",") : "#{questid}-#{i.attributes['Amount']}"
      end
    end
    rewards.each_pair do |itemid, questlist|
      $sql.query("UPDATE server_item_idtable SET questreward='#{questlist}' WHERE itemid='#{itemid}'")
    end

    log "  ... Done."
    
    #finalize
    trimsql("server_item_idtable")



    #  //auslesen der itemtypes to flobase_item_types ... no need for, do we?
    #  // MYSQL_ERRORs werden angezeigt -> columnname

	#		$queryitemtable = MYSQL_QUERY("SELECT * FROM server_item_idtable GROUP BY tableid");
	#		while ($itemtable = MYSQL_FETCH_ARRAY($queryitemtable)) {
	#			$queryitemtype = MYSQL_QUERY("SELECT * FROM server_item_".$itemtable['tableid']." GROUP BY ".$florensia->get_columnname("itemtype", "item")."");
	#			while ($itemtype = MYSQL_FETCH_ARRAY($queryitemtype)) {
	#					if (MYSQL_NUM_ROWS(MYSQL_QUERY("SELECT itemtypeid FROM flobase_item_types WHERE itemtypeid = '".$itemtype[$florensia->get_columnname("itemtype", "item")]."'"))==0) {
	#						MYSQL_QUERY("INSERT INTO flobase_item_types (itemtypeid ".$flolanguage().") VALUES('".$itemtype[$florensia->get_columnname("itemtype", "item")]."' ".flolanguage($itemtype[$florensia->get_columnname("itemtype", "item")]).")");
	#					}
	#			}
	#		}


##################
# Update flobase_item_categories
##################

log "Update flobase_item_categories..."
  i = 0
  $sql.query("SELECT COUNT(itemid), tableid FROM server_item_idtable GROUP BY tableid").each do |amount, tableid|
    valid = $sql.query("SELECT id FROM flobase_item_categories WHERE name_table = '#{tableid}' LIMIT 1").fetch_row
    if !valid then
      $sql.query("INSERT INTO flobase_item_categories (name_table, #{$settings['flobaselanguages'].dup.collect!{|x| "name_"+x}.join(", ")}, itemcount) VALUES('#{tableid}', #{(["'#{tableid}'"]*($settings['flobaselanguages'].length)).join(", ")}, '#{amount}')")
      i += 1
    else
      $sql.query("UPDATE flobase_item_categories SET itemcount='#{amount}' WHERE name_table='#{tableid}'")
    end
  end
log "  ... Done. Added #{i} entries."



##################
# Reading seal/itemeffects
##################

log "Reading seal-/item-effects... (.../Data/DataTable/ServerTable/s_SealOptionValueData.txt)"
if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_SealOptionValueData.txt") then
  log "Error: s_SealOptionValueData.txt does not exist! Nothing done." 
else

    doc = open($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_SealOptionValueData.txt").read.split("\n")
    s = {
      'defaulttype'=>"varchar(50) NOT NULL",
      'primary'=>"효과코드",
      'column'=>{'효과코드'=>"SMALLINT(6) NOT NULL DEFAULT '0'"},
    }
    file2sql("server_item_effect", [doc], s)
    
    i = 0
    log "  Look up if any new effects are added..."
    $sql.query("SELECT 효과코드, 이름, Operator FROM server_item_effect").each do |effectid, korean, operator|
      valid = $sql.query("SELECT effectid FROM flobase_item_effect WHERE effectid = '#{effectid}' LIMIT 1").fetch_row
      if !valid then
        $sql.query("INSERT INTO flobase_item_effect (effectid, code_operator, korean_name, #{$settings['flobaselanguages'].dup.collect!{|x| "name_"+x}.join(", ")}) VALUES('#{effectid}', '#{operator}', '#{korean}', #{(["'#{korean}'"]*($settings['flobaselanguages'].length)).join(", ")})")
        i += 1
      end
    end
    log "    ... Done. Added #{i} entries."
    log "  ... Done."
    
    trimsql("server_item_effect")
end


##################
# Reading spell/skill
##################
skillfiles = ["s_CharSpellAction.txt", "s_CharSkillAction.txt", "s_ShipSpellAction.txt", "s_ShipSkillAction.txt", "s_MobSkillAction.txt", "s_PetSkillAction.txt"]
docs = []
log "Reading skill/spell data..."
skillfiles.each do |f|
  if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/#{f}") then
    log "Error: #{f} does not exist! Nothing done." 
  else
    log "  Reading #{f}..."
    doc = open($settings['clientbasedir']+"/Data/DataTable/ServerTable/#{f}").read.split("\n")
    docs << doc
  end
end

s = {
  'defaulttype'=>"varchar(50) NOT NULL",
  'primary'=>columnname("skillid", "skill"),
  'column'=>{'skill_레벨'=>"SMALLINT(6) NOT NULL DEFAULT '0'"},
  'columnprefix'=>"skill_",
  'additionalcolumns'=>[
    "`skill_picture` varchar(20) NOT NULL"
  ]
}
file2sql('server_skill', docs, s) unless docs.empty?
log "  ... Done."


##################
# Look up if any new skill columns were created
##################

  log "Adding missing columns to flobase_skill_columns and save them into $settings['columns']['skill']..."
  i = 0
    columns = $sql.query("SHOW COLUMNS FROM server_skill").each do |c|
      next if /^name_/.match(c[0])
      valid = $sql.query("SELECT code_english FROM flobase_skill_columns WHERE code_korean='#{Mysql.escape_string(c[0])}'").fetch_row
      if !valid then
        i += 1
        log "    Adding #{c[0]}..."
        $settings['columns']['skill'][c[0]] = c[0]
        $sql.query("INSERT INTO flobase_skill_columns (code_english, code_korean, name, #{$settings['flobaselanguages'].dup.collect!{|x| "name_"+x}.join(", ")}) VALUES(#{(["'"+Mysql.escape_string(c[0])+"'"]*(3+$settings['flobaselanguages'].length.to_i)).join(", ")})")
      end
    end
  log "  ... Done. #{i} rows have been added."


##################
# Adding skill/spell pictures
##################
skillfiles = ["c_SpellActionRes.txt", "c_SkillActionRes.txt", "c_ShipSpellActionRes.txt", "c_ShipSkillActionRes.txt", "c_MSkillActionRes.txt", "c_PetSkillActionRes.txt"]
    
log "Loading skill/spell-images..."
skillfiles.each do |f|
  if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ClientTable/#{f}") then
    log "Error: #{f} does not exist! Nothing done."  
  else
      log "  Reading #{f}..."
      doc = open($settings['clientbasedir']+"/Data/DataTable/ClientTable/#{f}")
      header = doc.gets.chomp.split("\t")
      skillid = header.index(columnname("skillid", "skill").gsub(/^skill_(.+)$/i, '\1'))
      skillpic = header.index("아이콘코드")

      while (skillid && skillpic && l = doc.gets) do
        next if l.length<1
        l = l.chomp.split("\t")
        $sql.query("UPDATE server_skill SET skill_picture='#{l[skillpic]}' WHERE #{columnname("skillid", "skill")}='#{l[skillid]}'")
      end
  end
end
log "  ... Done."
trimsql("server_skill")


##################
# Reading Skilltrees GUI
##################

log "Loading Skilltree GUI (.../Data/UI/Xml/En/CharSkill.xml)..."
if !File.exists?($settings['clientbasedir']+"/Data/UI/Xml/En/CharSkill.xml") then
  log "Error: .../Data/UI/Xml/En/CharSkill.xml does not exist! Nothing done."  
else
  doc = REXML::Document.new File.open($settings['clientbasedir']+"/Data/UI/Xml/En/CharSkill.xml")
  rectinfostart = 0 #start at icon X
  #cleaning up
  $sql.query("DROP TABLE IF EXISTS server_skilltrees")
  $sql.query("CREATE TABLE IF NOT EXISTS `server_skilltrees` (
    `skilltreeid` varchar(64) NOT NULL,
    `xmltree` text NOT NULL,
    `rectinfostart` smallint(6) NOT NULL DEFAULT '0',
    PRIMARY KEY (`skilltreeid`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8")

  doc.elements.each("class/class[@name='UITabPane']") do |uitabpane| #search for global window
      uitabpane.elements.each("class[@name='UITabPanePage']") do |uitabpanepage| #search for land/see-tabs
        uitabpanepage.elements.each("class[@name='UIForm']") do |classpane| #search for classes
          log "  Loading..."
          log "    ID:   "+ classpane.elements['BaseInfo'].attributes['id']
          log "    LTWH: "+ classpane.elements['BaseInfo'].attributes['ltwh']
          
          #build basic xml
          xml = REXML::Document.new
          base = xml.add_element "skilltree"
          treeid = base.add_element "treeid"
          (treeid.add_element "id").add_text(classpane.elements['BaseInfo'].attributes['id'].strip)
          (treeid.add_element "coords").add_text(classpane.elements['BaseInfo'].attributes['ltwh'].strip)

          classpane.elements.each("class[@name='UIStaticImage']") do |img|
            log "    IMG:  Name    > "+img.elements['ImageInfo'].attributes['name'] #name
            log "          LTWH    > "+img.elements['BaseInfo'].attributes['ltwh'] #bordercoords
            log "          LTWH_LT > "+img.elements['ImageInfo'].attributes['ltwh_lt'] #coords
            pic = base.add_element "insertpic"
            (pic.add_element "name").add_text(img.elements['ImageInfo'].attributes['name'].strip)
            (pic.add_element "bordercoords").add_text(img.elements['BaseInfo'].attributes['ltwh'].strip)
            (pic.add_element "coords").add_text(img.elements['ImageInfo'].attributes['ltwh_lt'].strip)
          end
          classpane.elements.each("class[@name='UIIconSet']") do |rect|
            rect.elements.each("RectInfo") do |rect|
              #log "    RECT: " +rect.attributes['ltwh'] #icons coords
              (base.add_element "rectinfo").add_text(rect.attributes['ltwh'].strip) unless rect.attributes['ltwh'].eql?("0, 0, 32, 32")
              rectinfostart += 1
            end
            break;
          end
          log "    RECT: Found #{xml.elements.to_a("//rectinfo").length} entries. Starting at #{rectinfostart-xml.elements.to_a("//rectinfo").length}."
          
          output = String.new
          #temporaly ignore $stderr because rexml.write is depricated but don't know how to use Formatters :D
          #REXML::Element.write is deprecated.  See REXML::Formatters
          $stderr_backup = $stderr.dup
          $stderr.reopen("/dev/null", "w")
          #do it!
          xml.write(output, 0)
          #readding $stderr so we are sure to see any sql-errors in the next command ;)
          $stderr = $stderr_backup.dup
          $sql.query("INSERT INTO server_skilltrees (skilltreeid, xmltree, rectinfostart) VALUES('#{classpane.elements['BaseInfo'].attributes['id']}', '#{output}', '#{rectinfostart-xml.elements.to_a("//rectinfo").length}')")        
        end
      end
  end
  log "  ... Done."
end


##################
# Reading Skilltrees Icons/Skills
##################
log "Loading Skilltree icons/skills (.../Bin/FlorensiaEN.bin)..."
if !File.exists?($settings['clientbasedir']+"/Bin/FlorensiaEN.bin") then
  log "Error: .../Bin/FlorensiaEN.bin does not exist! Nothing done."  
else
  doc = open($settings['clientbasedir']+"/Bin/FlorensiaEN.bin", "rb")
  land = []
  sea = []
  content = doc.read

      content.scan(/(?:\x00)(cp[0-9]+|ck[0-9]+)(?:\x00)/).each do |s|
        land << s[0]
      end

      content.scan(/(?:\x00)(sk[a-z]+[0-9]+)(?:\x00)/).each do |s|
        sea << s[0]
      end
      
      #workaround to get rid of unused skillids in front of the listing
      #ck500000,ck900300,ck900900,cp901400,cp006600,cp007100,ck004100,ck500000,cp003400,cp006400,
      1.step(10, 1) do |i|
        land.shift
      end
      
      log "  Found #{land.length} land-skills."
      $sql.query("INSERT INTO server_skilltrees (skilltreeid, xmltree, rectinfostart) VALUES('rectinfolist_land', '#{land.join(",")}', '0')")
      log "  Found #{sea.length} sea-skills."
      $sql.query("INSERT INTO server_skilltrees (skilltreeid, xmltree, rectinfostart) VALUES('rectinfolist_sea', '#{sea.join(",")}', '0')")
  log "  ... Done."
end

##################
# Loading sealbreak cost data
##################

log "Loading Sealbreak data... (.../s_SealBreakCostData.txt)"
if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_SealBreakCostData.txt") then
  log "Error: s_SealBreakCostData.txt does not exist! Nothing done."  
else
  dbcolumns = "costid\titemlevel\tcost\tneeditem1\tneeditem1count\tneeditem2\tneeditem2count\tneeditem3\tneeditem3count\tneeditem4\tneeditem4count\tneeditem5\tneeditem5count"
  
  doc = open($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_SealBreakCostData.txt").read.split("\n")
  doc[0].replace(dbcolumns)
  s = {
    'defaulttype'=>"varchar(20) NOT NULL",
    'primary'=>"costid",
    'column'=>{
      'itemlevel'=>"SMALLINT(6) NOT NULL DEFAULT '0'",
      'cost'=>"SMALLINT(6) NOT NULL DEFAULT '0'",
      'needitem1count'=>"SMALLINT(6) NOT NULL DEFAULT '0'",
      'needitem2count'=>"SMALLINT(6) NOT NULL DEFAULT '0'",
      'needitem3count'=>"SMALLINT(6) NOT NULL DEFAULT '0'",
      'needitem4count'=>"SMALLINT(6) NOT NULL DEFAULT '0'",
      'needitem5count'=>"SMALLINT(6) NOT NULL DEFAULT '0'"
    },
  }
  file2sql("server_seal_breakcost", [doc], s)
  log "  ... Done."
  trimsql("server_seal_breakcost")
end


##################
# Loading sealbreak options
##################

log "Loading Sealbreak options..."
if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_SealOptionData.txt") then
  log "Error: s_SealOptionData.txt does not exist! Nothing done."  
else
  $sql.query("DROP TABLE server_seal_option")
  $sql.query("CREATE TABLE IF NOT EXISTS `server_seal_option` (
  `sealid` varchar(5) NOT NULL,
  `sealoption` text NOT NULL,
  PRIMARY KEY (`sealid`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;")
  
  doc = open($settings['clientbasedir']+"/Data/DataTable/ServerTable/s_SealOptionData.txt")
  doc.gets #we do not need the header
  
  while l = doc.gets
    l = l.chomp.split("\t")
    sealid = l.shift
    l.shift # also no need of the second column (korean name)
    sealoptions = l.join(",")
    $sql.query("INSERT INTO server_seal_option (sealid, sealoption) VALUES('#{sealid}', '#{sealoptions}')")
    
    valid = $sql.query("SELECT sealid FROM flobase_seal_optionlang WHERE sealid='#{sealid}'").fetch_row
      if !valid then
        log "  Adding missing #{sealid}..."
        $sql.query("INSERT INTO flobase_seal_optionlang (sealid, #{$settings['flobaselanguages'].dup.collect!{|x| "name_"+x}.join(", ")}) VALUES('#{sealid}', #{(["'#{sealid}'"]*($settings['flobaselanguages'].length.to_i)).join(", ")})")
    end
  end
  
  log "  ... Done."
end


##################
# Loading additional data
##################

log "Loading additional data (batch)..."
tables = []
$settings['additionalfiles'].each_pair do |table, file|
  if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ServerTable/#{file}") then
    log "Error: #{file} does not exist! Nothing done."  
  else
    log "  Reading #{file} to table #{table}..."
    doc = open($settings['clientbasedir']+"/Data/DataTable/ServerTable/#{file}").read.split("\n")
    s = {
      'defaulttype'=>"varchar(100) NOT NULL",
      'primary'=>"id",
      'additionalcolumns'=>[
        "`id` int(11) NOT NULL AUTO_INCREMENT"
      ],
      'columnprefix'=>"#{table}_"
    }
    file2sql(table, [doc], s)

    tables << table
  end 
end
log "  ... Done."

log "Adding missing columns to flobase_misc_columns and save them into $settings['columns']['misc']..."
i = 0
tables.each { |t|
      log "  #{t}..."
      columns = $sql.query("SHOW COLUMNS FROM #{t}").each do |c|
        next if /^(name_|id)/.match(c[0])
        valid = $sql.query("SELECT code_english FROM flobase_misc_columns WHERE code_korean='#{Mysql.escape_string(c[0])}'").fetch_row
        if !valid then
          i += 1
          log "    Adding #{c[0]}..."
          $settings['columns']['misc'][c[0]] = c[0]
          $sql.query("INSERT INTO flobase_misc_columns (code_english, code_korean) VALUES('#{Mysql.escape_string(c[0])}', '#{Mysql.escape_string(c[0])}')")
        end
      end 
}
log "  ... Done. #{i} rows have been added."


tables.each { |t| trimsql(t) }


##################
# Workaround to get petskillstones work properly
##################

log "Workaround to get petskillstones work properly"
log "  Adding names for each stone to server_stringtable..."
$sql.query("INSERT IGNORE INTO server_stringtable (Code, #{$settings['clientlanguage'].join(", ")}) SELECT item_코드 as itemID, #{$settings['clientlanguage'].dup.collect!{|x| "s."+x}.join(", ")} FROM server_item_petskillstoneitem as p, server_stringtable as s WHERE s.Code=p.item_대상코드")

log "  Loading skill-pictures..."
f = "c_PetSkillStoneItemRes.txt"
  if !File.exists?($settings['clientbasedir']+"/Data/DataTable/ClientTable/#{f}") then
    log "Error: #{f} does not exist! Nothing done."  
  else
      log "  Reading #{f}..."
      doc = open($settings['clientbasedir']+"/Data/DataTable/ClientTable/#{f}")
      header = doc.gets.chomp.split("\t")
      skillid = header.index(columnname("skillid", "skill").gsub(/^skill_(.+)$/i, '\1'))
      skillpic = header.index("아이콘") #skillpic = header.index("아이콘코드")

      while (skillid && skillpic && l = doc.gets) do
        next if l.length<1
        l = l.chomp.split("\t")
        #additional hack
        $sql.query("UPDATE server_item_petskillstoneitem, server_item_idtable SET server_item_idtable.itempicture='#{l[skillpic]}' WHERE server_item_petskillstoneitem.item_코드=server_item_idtable.itemid AND server_item_petskillstoneitem.item_대상코드='#{l[skillid]}'")
        ##end
        $sql.query("UPDATE server_skill SET skill_picture='#{l[skillpic]}' WHERE #{columnname("skillid", "skill")}='#{l[skillid]}'")
      end
  end
log "  ... Done."
