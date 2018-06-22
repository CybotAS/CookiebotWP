# How to block cookies

Cookiebot Addons uses different techniques to block cookies from 3rd party plugins. 

This document contains explainations of the classes and functions that are available.

List of included interfaces in every addon
--
- [Settings_Service_Interface](#settings-service-interface)
- [Script_Loader_Tag_Interface](#script-loader-tag-interface)
- [Cookie_Consent_Interface](#cookie-consent-interface)
- [Buffer_Output_Interface](#buffer-output-interface)

List of helpers to block cookies
---
- [remove_action](#remove-action)
- [cookiebot_remove_class_action](#cookiebot-remove-class-action)

# Settings Service Interface

The Settings Service is used to store and retrieve settings. 

Example settings: 

- Check if the addon is active or not.
- Get the placeholder text that should be displayed to renew a end-users consent
- Get the tracking types that the addon needs consent for

This will return the configured cookie types for an addon:
```
$this->settings->get_cookie_types( $this->get_option_name() );
```

# Script Loader Tag Interface

Script Loader Tag is used to manipulate the enqeueud scripts. It will add cookiebot script attributes to the script tag so the cookiebot can block the script while consent is not given.

Example:

Below function will manipulate scripts added with the handle 'addtoany'. The second parameter will send the cookie types that were selected in the administration UI for the addon at hand. Those types will be implemented into the data-cookieconsent attribute.
```
$this->script_loader_tag->add_tag( 'addtoany', $this->get_cookie_types() );

// will change the script tag into this
<script text='text/plain' data-cookieconsent='statistics,marketing'>
```

# Cookie Consent Interface

Cookie Consent will return the tracking types which the user has consented to (statistics,marketing etc.). If the necessary cookie types are selected, you can then skip the code to block the cookies because the consent is already given.

Example:
This will check if required consents are given.
```
if( $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ){
```

# Buffer Output Interface

The Buffer Output is used to manipulate the scripts which are hardcoded, and can't be hooked into with default functionality. There is no other way to block those cookies then to use buffer output manipulation.

Example: 

This will manipulate the output from wp_footer action hook starting from priority 9 till 11. And it will search for keys given in 3rd parameter(gtag, _gaq, ...). If it does find it then it will apply the cookie types that were selected in the administration UI for the addon at hand. If you don't want to use those types, you can change the parameter into a custom array like this: array('statistics', 'marketing').

```
$this->buffer_output->add_tag( 'wp_footer', 10, array(
				'gtag'                                 => $this->get_cookie_types(),
				'google-analytics'                     => $this->get_cookie_types(),
				'_gaq'                                 => $this->get_cookie_types(),
				'www.googletagmanager.com/gtag/js?id=' => $this->get_cookie_types()
			), false );
```

# Remove Action

Sometimes you have the option to block the action hook before the hardcoded script. Then you can use remove action to block the cookies.

Example:
```
remove_action(actionname, functionname);
```

```
if ( has_action( 'wp_head', 'A2A_SHARE_SAVE_head_script' ) ) {
	remove_action( 'wp_head', 'A2A_SHARE_SAVE_head_script' );
}
```


# Cookiebot Remove Class Action

Some action hooks are used in a class. By default WordPress doesn't have a feature to remove those action hooks. So we added our own helper function to do that.

Example:

This will remove the method pixel_init in the class of AEPC_Pixel_Scripts.

- wp_head = action hook
- AEPC_Pixel_Scripts = class name
- pixel_init = method name
- 99 = priority
```
cookiebot_remove_class_action( 'wp_head', 'AEPC_Pixel_Scripts', 'pixel_init', 99 );
```
