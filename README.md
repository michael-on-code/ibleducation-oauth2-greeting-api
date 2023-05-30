WP Rest API Plugin With OAuth2 Layer
========================

This plugin allows anyone to send via a REST API with a 
OAuth2 Layer via POST method a greeting message that'll appear on the 
WP Admin Dashboard

Requirements
------------

  * PHP 7.0 or higher;
  * WP Installation v5 or higher
  * Change your Wordpress Permalink structure to Post Name. 
  To do this, go to, Settings >> Permalinks >> Choose Post Name 
  as Permalink Structure. Save !
  

Installation
------------

* Insert these files in a folder (and name it maybe ibleducation-rest-oauth2-api)
  * Zip the folder
  * Upload the plugin zip file to into wordpress. In the administration go to 
  Plugins >> Add >> Upload 

OAuth2 Layer
-----

The OAuth2 Access Token URL : <site_url>/wp-json/greetingbot/v1/login

It requires : 

* Client ID : michaeloncode

* Client Secret : michaeloncode

* Grant Type : client_credentials

REST API
-----
The available REST API Endpoint is : <site_url>/wp_json/greetingbot/v1/send

It requires a body parameter : greeting

Ex :

```bash
{
   "greeting":"Welcome to wakanda"
}
```

If everything goes well, you'll receive a response like this :

```bash
{
    "message": "Greeting Message Updated Successfully",
    "status": true
}
```

Your latest sent greeting would be visible on 
the WP Admin Dashboard.

Enjoy !


