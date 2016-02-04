# Author Notice plugin

( Installation code : __kurtjensen.authnotice )
A new way for authors to speak to users who want to listen for October CMS.

## Reading Messages

Messages for your purchased or free plugins are automatically pulled from the URL resouce provided by their respective authors.  By installing this plugin you will recieve notices at the following times:
- When pressing "Request Messages" button on **Author Notices > Read** backend page.
- When using the Notice Alert widget, Messages are pulled each time the back-end dashboard is opened.

## Plugin settings

This plugin creates a Settings menu item, found by navigating to **Author Notices->Read** or **Settings > Author Notices**. This page allows the setting of common features, described in more detail below.
- For reading only you should set your **End User Settings**
    - Message Retention - Sets how many days you want to retaing messages that are marked as read?  (These will not be deleted on purge.) A value of 0 will delete all messages that are marked as read on purge. ( default: 30 )
    - Auto Purge - If check all read messages that are past retention date automatically purge from the database.
    - Reader Language - Language code for the language that you prefer notes be translated to. This is for a link in the reader that will have Google Translate any messages not in your language to your language for reading. ( default: en )( english )


- For author who will be creating messages you need to set your **Author Settings**
    - Plugin Name Spaces - The plugin namespaces (dot notation) for your plugins. Seperate multiple plugin namespaces with commas. Example : AwsomeDevel.MyPlugin,AwsomeDevel.MyOtherPlug
    - Author Language - Language code for the language that your notes will be sent in. ( default: en )( english )
    - YOUR PLUGINS WILL NEED a URL added in the Plugin.php file.

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
                'message_url' => 'http://tosh/~kurt/october/authserve/',
            ];
        }
```    

## Creating Messages for your Plugin

Users are managed on the Users tab found in the back-end. Each user provides minimal data fields - **Name**, **Surname**, **Email** and **Password**. The Name can represent either the person's first name or their full name, making the Surname field optional, depending on the complexity of your site.

