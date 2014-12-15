require 'net/http'

# Return list of web-servers for given environment
def web_servers(stage)
    uris = {
        'staging' => 'staging_servers',
        'testing' => 'staging_servers',
        'production' => 'ec2_web_servers'
    }
    return '' if !uris.include?(stage)
    res = Net::HTTP.get_response URI.parse("http://services.misc.stuzo.com/#{uris[stage]}")
    res.body.split("\n").map{|str| str.gsub(/<\/?[^>]*>| */, "")}
end

# Upgrade image of EC2 server for given environment
def upgrade_image(stage)
    Net::HTTP.get_response URI.parse("http://misc.stuzo.com/capistrano/image/image.php?env=#{stage}")
end