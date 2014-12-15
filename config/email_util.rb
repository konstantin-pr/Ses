require 'mail'
require 'erb'

# Mail configuration
# Using Amazon SES service
set :smtp_account, 'AKIAJROC76SCRJJWTVQQ'
set :smtp_password, 'AmYotd5fvBn0un7RbBLwSRiO5UrXsPDy+Q1XQq55bTOa'
set :smtp_server, 'email-smtp.us-east-1.amazonaws.com'
set :smtp_port, 587
set :email_from, 'Deployment Bot <deployment@stuzo.com>'

namespace :deploy do
  desc 'Notify by email that deployment was successful'
  task :email_notify, :roles => :admin do
    if ENV.has_key?('noemail') then
      next
    end
    if !email_to.empty? then
      template = File.open('config/mail.template.erb', 'rb').read
      formatted_message = ERB.new(template).result(binding)
      send_email(
      email_to,
      email_from,
      "Deployment: #{application} (#{branch}) is deployed to #{stage}",
      formatted_message,
      {:server => smtp_server, :port => smtp_port,
        :user_name => smtp_account, :password => smtp_password,
        :domain => 'stuzo.com'})
    end
  end
end

# Send mail routine
# using 'mail' gem (https://github.com/mikel/mail)
def send_email(recipient, sender, subject, message, opts = {})
  opts[:server]      ||= 'localhost'
  opts[:port]        ||= 25
  Mail.defaults do
    delivery_method :smtp, {
      :address              => opts[:server],
      :port                 => opts[:port],
      :domain               => opts[:domain],
      :user_name            => opts[:user_name],
      :password             => opts[:password],
      :authentication       => 'plain',
      :enable_starttls_auto => true}
  end
  mail = Mail.new do
    from    sender
    to      recipient
    subject subject

    html_part do
      content_type 'text/html; charset=UTF-8'
      body message
    end
  end
  mail.deliver!
end