# BOT Child Theme

Built as a child theme of the [UCF WordPress Theme](https://github.com/UCF/UCF-WordPress-Theme) for the Board of Trustees website, using the [Athena Framework](https://ucf.github.io/Athena-Framework/).

## Quick links

* [**Theme Documentation**](https://github.com/UCF/BOT-Child-Theme/wiki)
* [Development](#development)
* [Contributing](#contributing)

-----

## Documentation

Head over to the [BOT Child Theme wiki](https://github.com/UCF/BOT-Child-Theme/wiki) for detailed information about this theme, installation instructions, and more.

-----

## Changelog ##

### 1.3.0 ###
Enhancements:
* Added fields and updated templates to support Board "Retreat/Workshop" meetings as separate from "Other" meetings.

### 1.2.2 ###
Bug Fixes:
* Modified the arguments used to get the latest meeting minutes to ensure meetings where the minutes have been removed at some point are excluded.

### 1.2.1 ###
Enhancements:
* Added ability to specify custom committee archive messages

### 1.2.0 ###
New Feature:
* Added a committee archive template and a link to said page below the list of committees in the sidebar. The committee archive page must be created to support this change.

-----

## Development

Note that compiled, minified css and js files are included within the repo.  Changes to these files should be tracked via git (so that users installing the theme using traditional installation methods will have a working theme out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

### Requirements
* node v16+
* gulp-cli

### Instructions
1. Clone the BOT-Child-Theme repo into your local development environment, within your WordPress installation's `themes/` directory: `git clone https://github.com/UCF/BOT-Child-Theme.git`
2. `cd` into the new BOT-Child-Theme directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Optional: If you'd like to enable [BrowserSync](https://browsersync.io) for local development, or make other changes to this project's default gulp configuration, copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.

    To enable BrowserSync, set `sync` to `true` and assign `syncTarget` the base URL of a site on your local WordPress instance that will use this theme, such as `http://localhost/wordpress/my-site/`.  Your `syncTarget` value will vary depending on your local host setup.

    The full list of modifiable config values can be viewed in `gulpfile.js` (see `config` variable).
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment, and [install and activate theme dependencies](https://github.com/UCF/BOT-Child-Theme/wiki/Installation#installation-requirements).
5. Set BOT Child Theme as the active theme.
6. Make sure you've completed [all theme configuration steps](https://github.com/UCF/BOT-Child-Theme/wiki/Installation#theme-configuration).
7. Run `gulp watch` to continuously watch changes to scss and js files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when scss or js files change.


## Contributing

Want to submit a bug report or feature request?  Check out our [contributing guidelines](https://github.com/UCF/BOT-Child-Theme/blob/master/CONTRIBUTING.md) for more information.  We'd love to hear from you!
