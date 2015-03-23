#Meggie Rose

Redesign for http://memorabella.com

##Info

Subdomain: http://memorabella.wyattmeade.com
Local: http://localhost/memorabella

##Setup

1. Create a project folder called `memorabella`.
2. Open up Terminal, navigate to that project folder and type the command: `git clone https://github.com/harlem-digital/meggierose.git`.
3. Then type the command: `npm install`.

Note: There is a local database version you can setup. Just make sure the alias matches the "Local URL" above.

##Grunt

Project uses Grunt tasks:

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