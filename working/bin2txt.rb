#!/usr/bin/env ruby
# encoding: utf-8
runtime = Time.new

if ARGV.empty? then
  puts "Filelist is emty."
  exit(404)
end

#require 'iconv'

def columntypes(t)
    case(t)
      when 0
        return 4
      when 1
        return 4
      when 2
        return 4
      when 3
        return 12
      when 4
        return 32
      when 5
        return 128
    end
    return 0
end


ARGV.each do |inputfile|
  if !File.exists?(inputfile) then
    puts "File not found (#{inputfile})"
    next
  else
    outputfile = File.split(inputfile)[0] +"/"+ File.basename(inputfile, ".bin")+".txt"
    File.delete(outputfile) if File.exists?(outputfile)
    output = File.new(outputfile, File::CREAT|File::APPEND|File::WRONLY, 0777)
    puts "Input:          #{inputfile}"
    puts "Output:         #{outputfile}"
  end

  doc = File.open(inputfile, "rb")
  
  bin = {
    'datasets'=>doc.read(4).unpack("l*")[0],
    'datasetslength'=>doc.read(4).unpack("l*")[0],
    'columns'=>doc.read(4).unpack("l*")[0]
  }
  
  puts "Datasets:       #{bin['datasets']}" #Number of the datasets or "lines"
  puts "Datasetlength:  #{bin['datasetslength']}" # Length of one dataset in bytes
  puts "Columns:        #{bin['columns']}" # Number of the columns or "rows"
  
  columns = Array.new
  bin['columns'].times do |i|
    #columns << {'name'=>Iconv.iconv('utf-8', 'EUC-KR', doc.read(32).gsub(/\x00.*/, ""))[0].strip, 'length'=>columntypes(doc.read(4).unpack("l*")[0])}
    columns << {'name'=>doc.read(32).gsub(/\x00.*/, "").encode('utf-8', 'EUC-KR').strip, 'length'=>columntypes(doc.read(4).unpack("l*")[0])}
  end
  #puts "--- HEADER (#{columns.length})---"
  #puts "#{columns.collect{|x| "#{x['name']} (#{x['length']})"}.join(", ")}"
  puts "Header found:   #{columns.length}/#{bin['columns']}"
  output.write("#{columns.collect{|x| x['name']}.join("\t")}\n")
  
  datasets = Array.new
  bin['datasets'].times do |d|
    #puts "#{d+2} -->"
    trash = doc.read(4)
    dataset = Array.new
    columns.each do |c|
      data = doc.read(c['length'])
      if c['length']!=4 then
        #definitely, it is a string
        #puts "Value: #{data.inspect}"
        begin 
        #dataset << Iconv.iconv('utf-8', 'EUC-KR', data.gsub(/\x00.*/, ""))[0].chomp
        dataset << data.gsub(/\x00.*/, "").encode('utf-8', 'EUC-KR').chomp
        rescue
          #workaround for strings with the 2 bytes are invalid (c_CloakItemRes.bin, l.1198-1212, "떤무늬기모노(검은색)#)
          #dataset << Iconv.iconv('utf-8', 'EUC-KR', data[2..(data.length-1)].gsub(/\x00.*/, ""))[0].chomp
          dataset << data[2..(data.length-1)].gsub(/\x00.*/, "").encode('utf-8', 'EUC-KR').chomp
        end
      else
        #float/integer
        dataset << data.unpack("L")[0]
      end
    end
    #p dataset
    datasets << dataset
    #puts "<--"
  end
  
  #puts "--- DATASETS (#{datasets.length})---"
  #puts "#{datasets.collect{|x| x.join(",")}.join("\n")}"
  puts "Datasets found: #{datasets.length}/#{bin['datasets']}"
  output.write("#{datasets.collect{|x| x.join("\t")}.join("\n")}")
  output.close
  puts "Finished:       #{(Time.new-runtime).to_f}s"
  puts "-----------"

end
