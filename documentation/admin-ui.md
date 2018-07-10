# How to manage the addon in admin ui

Every addon can be configured in the admin ui.

This document contains explainations of the configurable options.

# Tabs

Cookiebot Addons settings page has 3 tabs:
- [Available plugins](#available-plugins)
- [Unavailable plugins](#unavailable-plugins)
- [Jetpack](#jetpack)

# Available plugins

In this screenshot you see the plugins that are installed and activated.

![available plugins][available-plugin]

In this section you manage the available plugins for cookiebot.

Example settings:
- disable addon
- Select required cookie types
- Add placeholder text in multi languages

In order to create unique placeholder experience, we have added 2 merge tags you can use in the placeholder text:
- ```%cookie_types``` Displays required cookie types to enable the addon.

- ```%src``` -> src url for youtube, facebook, vimeo and twitter videos. Currently it only works for Embed addon.

# Unavailable plugins

In this screenshot you see the plugins which are not activated or installed.

![unavailable plugins][unavailable-plugin]

# Jetpack

Jetpack has one more tab to configure the widgets. 

![jetpack][jetpack]

[available-plugin]: assets/available-plugins.png
[unavailable-plugin]: assets/unavailable-plugins.png
[jetpack]: assets/jetpack.png