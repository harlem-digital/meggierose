#Meggie Rose

Redesign for http://memorabella.com

##Info

Subdomain: http://memorabella.wyattmeade.com
Local: http://localhost/memorabella

###Theme

Memorabella is the new WP Theme. It's using a CSS Framework crafted by Ken Jackson.

####css/app

This houses all of the helpers (mixins, functions, default variables, etc). There should be no real need to go into this directory unless there is a bug that needs to be fixed. Any variables defined are marked as !default and can be overriden within the theme directory.

####css/theme

This will house all of the helpers (mixins, functions, variables, etc) that are specific to this particular theme. You can override any variables here, and you can define new variables / functionality within this directory.

####css/pages

This houses all page specific CSS. Not every page will need a CSS file, but if there is custom styling for a particular page you can create a CSS file in this directory. For example, 'home.css' would go in here and will have CSS only that the homepage might have.

####css/global.css

Any global styling that is shared between all pages will go here. For example, styling for the header or footer might go in here if it's shared across all pages.

##Setup

1. Create a project folder called `memorabella`.
2. Open up Terminal, navigate to that project folder and type the command: `git clone https://github.com/harlem-digital/meggierose.git`.
3. Then type the command: `npm install`.

Note: There is a local database version you can setup. Just make sure the alias matches the "Local URL" above.

##Grunt

Project uses Grunt tasks:

* sass
* autoprefixer
* jshint
* cssmin
* uglify

###Watch

To watch changes to files in a theme.

```
grunt watch -theme=theme-name
```

###Build

To build files for production.

```
grunt build -theme=theme-name
```