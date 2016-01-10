# Require any additional compass plugins here.
# require "compass-growl"
# require "compass-normalize"
# require "susy"
# require 'compass-recipes'

# General
app_path = "src/"
add_import_path app_path + "components/"

# HTTP Paths
http_path = "../"
http_javascripts_path = http_path + "scripts/"
http_stylesheets_path = http_path + "css/"
http_images_path = http_path + "images/"
http_generated_images_path = http_images_path
http_fonts_path = http_stylesheets_path + "fonts/"

# Sass Directories
javascripts_dir = app_path + "scripts/vendor/"
css_dir = "tmp/styles/"
sass_dir = app_path + "styles/"
images_dir = app_path + "images"
generated_images_dir = images_dir
fonts_dir = sass_dir + "fonts/"
