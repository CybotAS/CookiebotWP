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

In order to create unique placeholder experience, we have added few merge tags you can use in the placeholder text:
- ```%cookie_types``` 
Displays required cookie types to enable the addon.

- ```%src``` 
src url for youtube, facebook, vimeo and twitter videos. Currently it only works for Embed addon.

- ```[renew_consent]``` and ```[/renew_consent]``` 
Both tag will be replaced by a link element with a href to the cookiebot settings box, so the visitor can enable or disable the cookie types. 


On top of that, there are also few filters to manipulate the placeholders:

#### Youtube, Vimeo and Facebook

- ```add_filter('cybot_cookiebot_addons_embed_source', $source)```   

This filter is used to manipulate the source attribute for embedded video

- ```add_filter('cybot_cookiebot_addons_embed_placeholder', $content, $source, $cookie_types)```

This filter is used to manipulate the placeholder output


# Unavailable plugins

In this screenshot you see the plugins which are not activated or installed.

![unavailable plugins][unavailable-plugin]

# Jetpack

Jetpack has one more tab to configure the widgets. 

![jetpack][jetpack]

[available-plugin]: assets/available-plugins.png
[unavailable-plugin]: assets/unavailable-plugins.png
[jetpack]: assets/jetpack.png
