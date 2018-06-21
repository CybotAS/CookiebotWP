# How to block cookies

Cookiebot Addons Framework uses different way to block cookies from 3rd party plugins. In this documentation we will explain you with the classes and functions we use to block those.

List of included interfaces in every addon
--
- [Settings_Service_Interface](#settings-service-interface)
- [Script_Loader_Tag_Interface](#script-loader-tag-interface)
- [Cookie_Consent_Interface](#cookie-consent-interface)
- [Buffer_Output_Interface](#buffer-output-interface)

List of helpers to block cookies differently
---
- [remove_action](#remove-action)
- [cookiebot_remove_class_action](#cookiebot-remove-class-action)

# Settings Service Interface

Settings Service is used to get settings information. For example to see if the addon is installed or not. For example to get the placeholder text.

For example this will return the selected cookie types in the backend:
```
$this->settings->get_cookie_types( $this->get_option_name() );
```

# Script Loader Tag Interface

Script Loader Tag is used to manipulate the enqeueud scripts. It will add cookiebot script attributes to the script tag so the cookiebot can block the script while the consent is not given.

For example:
This will manipulate scripts added with the handle 'addtoany'. The second parameter will send the cookie types that were selected in the backend. Those types will be implemented into the script attributes.
```
$this->script_loader_tag->add_tag( 'addtoany', $this->get_cookie_types() );

// this will change the script tag into this
<script text='text/plain' data-cookieconsent='statistics,marketing'>
```

# Cookie Consent Interface

Cookie Consent will return the selected cookie types in PHP. So you can check if the cookie types are selected or not. If the necessary cookie types are selected, you can then skip the code to block the cookies because the consent is already given.

For example:
This will check if requested consents are given.
```
if( $this->cookie_consent->are_cookie_states_accepted( $this->get_cookie_types() ) ){
```

# Buffer Output Interface

The Buffer Output is used to manipulate the scripts which are hardcoded inserted. There is no other way to block those cookies then to use buffer output manipulation.

For example: This will manipulate the output from wp_footer action hook starting from priority 9 till 11. And it will search for keys given in 3rd parameter(gtag, _gaq, ...). If it does find it then it will apply the cookie types that were selected in the backend. If you don't want to use that types, you can change that into a custom array like this: array('statistics', 'marketing').
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

For example:
```
remove_action(actionname, functionname);
```

# Cookiebot Remove Class Action

Some action hooks are used in a class. The default WordPress doesn't have a feature to remove those action hooks. So we added our own helper function to do that.

For example:
This will remove the method pixel_init in the class of AEPC_Pixel_Scripts.

- wp_head = action hook
- AEPC_Pixel_Scripts = class name
- pixel_init = method name
- 99 = priority
```
cookiebot_remove_class_action( 'wp_head', 'AEPC_Pixel_Scripts', 'pixel_init', 99 );
```