# Dev4Press Library

## Changelog

### Version 5.0.1 - 2024.08.22

* Edit: updated links format for the Dev4Press website

### Version 5.0 - 2024.07.10

* New: updated library and plugins system requirements
* New: extracted `Dialogs` JavaScript library from `Admin`
* New: added several more icons to the icon font
* Edit: default location is now `library`
* Edit: many updates to the `Background` class
* Edit: many updates to the `CRON` task class
* Edit: various tweaks to the shared styling
* Removed: outdated JavaScript `helpers` library
* Removed: all previously deprecated methods

### Version 4.9.2 - 2024.06.26

* Edit: expanded list of detected email types to include coreSecurity Pro
* Edit: various styling improvements to all the grid pages
* Edit: improvements to the `Features` loading for the main site only features
* Fix: few minor issues with the handling of the feature settings
* Fix: minor problems with the standard set of `KSES` tags and attributes

### Version 4.9.1 - 2024.06.12

* Edit: few tweaks and improvements to the license code validation
* Fix: license code validation shows wrong information in some cases

### Version 4.9 - 2024.06.03

* New: default styling for the `Micromodal` JS modal library
* New: added several more icons to the icon font
* Edit: additional optional settings for some of the option types 
* Removed: use of `EOT` font from the icon font stylesheets
* Removed: all the previously included `MO` translation files
* Fix: several issues when saving plugin boolean settings
* Fix: an expandable pair option can skip new values on saving

### Version 4.8 - 2024.04.26

* New: `Core` object has priority options for the loading actions
* New: `Settings` object can specify some groups as network wide
* New: settings now can render the `Clear` button for text-based field
* New: checkbox based options can be used for the option switch control
* New: check for capability for postback for main plugin panels
* New: rewritten most of the settings processing for better validation
* New: `Sanitize` class has new method for `deep` processing
* New: `Sanitize` class renames some methods with proper names
* New: added several more UI icons to the icon font
* Edit: improvements to the default styling for dashboard and tabs
* Edit: improvements to base `Panel` object content file names
* Edit: improvements to the `License` base class related to API updates
* Edit: improvements to the autoload function implementation
* Edit: improvements to import of plugin settings
* Edit: various improvements to the data processing and saving
* Edit: outdated actions prefixes replacing `d4p` with `dev4press`
* Edit: various improvements to sanitization and escaping
* Edit: built-in cacert.pem 20240311
* Deprecated: several methods in the `Sanitize` class
* Fix: some `Core` properties were private not protected
* Fix: license check job initialized with filter not action
