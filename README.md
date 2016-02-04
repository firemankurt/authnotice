# Author Notice plugin

( Installation code : __kurtjensen.authnotice )
A new way for authors to speak to users who want to listen for October CMS.

## Reading Messages

Messages for your purchased or free plugins are automatically pulled from the URL resouce provided by their respective authors.  By installing this plugin you will recieve notices at the following times:
- When pressing "Request Messages" button on **Author Notices > Read** backend page.
- One time each day if you choose "Auto Retrieve" in settings.

You will only get messages for plugins you have installed.  Authors should not be using this for any kind of marketing.  It is intended for a way of letting you know about changes that may affect the operation of their plugins on your site.

## Plugin settings

This plugin creates a Settings menu item, found by navigating to **Author Notices** page in the top menu or **Settings > Author Notices**. This page allows the setting of common features, described in more detail below.
- For reading only you should set your **End User Settings**
    - Message Retention - Sets how many days you want to retaing messages that are marked as read?  (Newer messages will not be deleted on purge.) A value of 0 will delete all messages that are marked as read on purge. ( default: 30 )
    - Auto Purge - If checked, all read messages that are past retention date automatically purge from the database daily. ( Requires proper setup of scheduler in October CMS )
    - Reader Language - Language code for the language that you prefer notes be translated to. This is for a link in the reader that will have Google Translate any messages not in your language to your language for reading. ( default: en )( english )
    - Auto Retrieve - If checked, messages will automatically be fetched from servers each day. ( Requires proper setup of scheduler in October CMS )

## Creating Messages for your Plugin

## Plugin settings

- For author who will be creating messages you need to set your **Author Settings**
    - Plugin Name Spaces - The plugin namespaces (dot notation) for your plugins. Seperate multiple plugin namespaces with commas. Example : AwsomeDevel.MyPlugin,AwsomeDevel.MyOtherPlug
    - Author Language - Language code for the language that your notes will be sent in. ( default: en )( english )

## Setup a Service Page

- Add a page to your website
- Drag and drop the **AuthNotice Service** component onto the page
- URL should be short and simple in the form of "/servicepage/:slug/:last_id"
    - :slug - will accept the plugin namespace
    - :last_id - represents the last message id client recieved for the requested plugin
        - After you make a message you should be able to test your service by entering a URL like "http://example.com//servicepage/Author.Plugin/0".
        - Result should be a JSON output of your messages for that plugin.


## Your Plugins

- YOUR PLUGINS WILL NEED a "message_url" added in the Plugin.php file if they are going to request messages.

```
    class Plugin extends PluginBase
    {

        /**
         * Returns information about this plugin.
         *
         * @return array
         */
        public function pluginDetails()
        {
            return [
                'name' => 'MyPlugin',
                'description' => 'Adds great feature to October CMS.',
                'author' => 'Me',
                'icon' => 'icon-leaf',
                'message_url' => 'http://example.com/authserve/',
            ];
        }
```
## Write your message

Navivate to to **Author Notices->Create**.
    - Choose what plugin this is for ( Thease are populated in Author section of Settings )
    - Choose a "Level" appropriate for your message.  Here are some basic guidlines:
        - Info - You just want to make user aware of something.
        - Warning - There is a minor issue, or change coming that may be noticed by users.
        - Critical - There is a major change in the way your plugin works that will require action on the part of the user.
        - Question - Your thinking about making a change and want to show users a way to give feeback about your idea before making changes to the plugin.
    - Enter your message text.
        - Do not use HTML.
        - This is not a marketing tool.
        - Keep it short and non-invasive.
    - When you are ready to release your message into the wild, check "Release for Sending". This will:
        - Set the release date
        - Make message avaiable for users to download.

You can delete expired messages or uncheck "Release for Sending".  This will stop it from being sent in any future requests. Please note that rechecking "Release for Sending" does enable it again but does not update the relase date.

## Like this plugin?
If you like this plugin or if you use some of my plugins, you can help me by submiting a review in the market. Small donations also help keep me motivated. 

Please do not hesitate to find me in the IRC channel or contact me for assistance.
Sincerely 
Kurt Jensen