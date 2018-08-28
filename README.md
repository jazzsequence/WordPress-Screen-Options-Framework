# WordPress Screen Options Framework

Quick start, plug and play include for extending and creating Screen Options in the WordPress admin.

## How to use

### As a plugin
I've built this as a WordPress plugin, so you can clone or download the repository, throw it into your `wp-content/plugins` folder and activate to see it working. This allows you to use this repository as a learning tool to understand how the Screen Options API works.

### As a library
You can use this project as a boilerplate/framework to include in your projects. In this case, you'll want to [download the `screen-options.php` file directly](https://raw.githubusercontent.com/jazzsequence/WordPress-Screen-Options-Framework/master/src/screen-options.php). There are a few things you'll need to change for your project, most notably the admin page ([here](https://github.com/jazzsequence/WordPress-Screen-Options-Framework/blob/master/src/screen-options.php#L64), [here](https://github.com/jazzsequence/WordPress-Screen-Options-Framework/blob/master/src/screen-options.php#L95) and [here](https://github.com/jazzsequence/WordPress-Screen-Options-Framework/blob/master/src/screen-options.php#L172)), the method by which you loop through options ([here](https://github.com/jazzsequence/WordPress-Screen-Options-Framework/blob/master/src/screen-options.php#L79) and [here](https://github.com/jazzsequence/WordPress-Screen-Options-Framework/blob/master/src/screen-options.php#L100)) and, obviously all the names for things.

### As a Sublime Text snippet
I've also built a robust snippet for Sublime Text. [Download the `sublime-snippet`](https://raw.githubusercontent.com/jazzsequence/WordPress-Screen-Options-Framework/master/sublime/wordpress-screen-options.sublime-snippet) and save it to your `~/Library/Application Support/Sublime Text 3/Packages/User` folder (on Mac, `%AppData%\Sublime Text 3\Packages\` on Windows). Create a `php` document and type `wp_screen_options` to trigger the snippet. Then just tab through the options to add in your own values. It's recommended that you at least look at the [source](https://github.com/jazzsequence/WordPress-Screen-Options-Framework/blob/master/src/screen-options.php) or try out the plugin before using the snippet so you know how it works.

## What it does

Adds unique screen options to the given admin page, loops through an array of options provided and adds a checkbox to toggle that option.

The checkbox input method is arbitrary -- any input field and type of data could be saved to user meta via screen options and additional field types could be added for more robust support.

With the plugin, an admin page is added which displays the value of the test screen options added to that page.

### More info coming soon...

