Movim Changelog
================

v0.13.1 (trunk)
---------------------------
 * Add a picture picker when sharing a URL in a post
 * Merge Publish in PublishBrief
 * Implement XEP-0157 to allow users to contact their administrators
 * Change the Reply button to Share
 * Add a spoiler on NSFW articles in the news feed
 * Show a spoiler on NSFW posts when the filter is enabled in News
 * Enhancements on Visio and CSS improvements
 * Fix date display in Chat on instable connectivity
 * Add a Preview widget to allow previsualisation of pictures in Movim
 * Fix compatibility with PHP 5.6
 * Code cleanup

v0.13
---------------------------
 * Update ReactPHP
 * Use PHP ZeroMQ to manage the communications between the processes
 * Cleanup some existing buffers
 * Add a pure HTTP ajax endpoint for some futur requests that needs it
 * Add some slight animations in the UI
 * Add a nightmode
 * Cleanup and refactorize some CSS (colors, forms)
 * Improve the connectivity UX status of chatrooms
 * Publish the chat messages using Ajax
 * Improve the configuration of Communities
 * Update the OpenSans font

v0.12.1
---------------------------
 * Add xmpp: uri to public pages headers
 * Code cleanup (by RyDroid)
 * Remove gender, marital and the Skype/Twitter/Yahoo account info
 * Fix Content-Security-Policy
 * UI improvement for the bottom navigation on mobile
 * Cleanup the Privacy Model
 * Set a max-width for the picture preview in Upload
 * Add application/javascript header to prevent MIME type checking issue
 * Redesign the Communities page
 * Remove the CommunitiesDiscover widget

v0.12 – Lovejoy
---------------------------
 * Add autojoin support for chatrooms
 * New Contact page
 * Improve Posts tags detection and navigation
 * New system to recover the session quickly
 * New PublishBrief widget
 * Add support for MUC invitations
 * Don't notify if the user is not in the Roster
 * UI optimisations
 * Better integration for Youtube videos
 * Add support of XEP-0333: Chat Markers
 * Update the translations
 * New design for the post Material Design cards
 * New UI for adding a contact through a Gateway (by singpolyma)
 * Allow users to clear their information on the instance and leave it properly
 * Add NSFW filter configuration
 * Save Draft of publications in Publish and PublishBrief
 * Add touch support to open the menu on mobile devices
 * Improve Stickers picker
 * Display more information in the Rooms list
 * Suggest public and open chatrooms on the Chat page
 * New navigation menu for mobile devices
 * Rotate correctly the JPEG files when uploading them
 * Add support of private MUC messages
 * Redesign of the Community main page
 * Refactor and cleanup the management of the comments internaly
 * Autocomplete nicknames in MUC using tabulation (by pztrn)
 * Add picture preview when posting links in MUC
 * Redesign the MUC bubbles to unify the style with the simple chats
 * Enable history for MUC
 * Pictures can be previewed before upload
 * Do not send the message when carriage return is pressed on mobile
 * More colors!
 * Protect pictures URLs with a HTTP HEAD check
 * Add Miho sticker pack
 * Add support of MAM (up to mam:2) for the MUCs

v0.11 – Tuttle
---------------------------
 * Navigation improvement
 * Add previous/next post shortcut in the footer of each posts
 * Highlight mentionned messages in chatrooms
 * Non alpha-numeric Pubsub items and nodes support
 * Non alpha-numeric JID support
 * Fix Markdown links with underscores
 * Fix two-way contact subscription button in Contact
 * New simplified and optimized Roster
 * Improved search (global and roster)
 * CSS fixes
 * Refactoring of the groups page UI and UX
 * Add (small) picture embeding in chats
 * Various speed optimisation
 * Add reply feature of existing posts
 * New and improved Share widget, now supports xmpp: links
 * New Stickers!
 * Big refactoring of the Groups, now called Communities with improved navigation and discovery features
 * Also refactor the Post widget
 * Add an Onboarding widget with some advices
 * Add Like feature
 * New Notifications widget to keep track of the comments and likes
 * Improvements in Carbons feature
 * Improve the Stickers picker
 * Refactor and cleanup the session management

v0.10 – Holmes
---------------------------
 * Resize and compress large pictures in Upload
 * Refactor MovimWebsocket and fix disconnection issues
 * Remove and cleanup old code
 * Handle errors when uploading large files
 * New bubble merging algorythm in the Chat
 * Improve UI and mobile UX on low resolution devices
 * New widget Drawer used for the stickers and the search form
 * Fix behaviour for Android and Electron packages
 * Fix Pubsub metadata handling for some XMPP servers
 * Add global search
 * Add silent notifications for chatrooms
 * Add alternate nickname support (adding "_") when joining a chatroom
 * Allow room configuration edition
 * Put your own XMPP server as default in the configuration (movim.eu in fallback)
 * Close the Dialog box when pressing ESC
 * Moving values from Sessionx to Session
 * Using chart.js for the statistics
 * Refactor the "public" system for the Posts
 * CSS fixes
 * Add Last Message Edition support
 * Improve Post discovery in the News page
 * Add stickers support
 * Improve loading time for Chat page
 * Improve Chat bubbles display
 * New compact date display
 * Clean properly the tags in the database
 * Allow tags with special characters
 * Various UI and navigation fixed
 * Use UUID as identifiers for the messages and posts
 * Delete properly the comments when deleting a post
 * Update the dependencies
 * Create an internal API to save some memory and improve session handling
 * Improve image handling in posts
 * Improve overall performances

v0.9 – Tchouri
---------------------------
 * New User Interface for the whole project
 * Removed BOSH connections and introduce pure XMPP TLS connections
 * Full real-time + daemon
 * New Blog engine and custom CSS support
 * New post publication system + attachements supported (upload and embed links)
 * Fully responsive design UI based on Material Design
 * Huge code cleanup and refactoring
 * Updated i18n system and new languages
 * New eventing system
 * New administration panel
 * New dedicated chat page and emojis support
 * New project icon and favicon
 * New implementation for the Groups feature
 * New Roster based on Angular
 * Refactor the Contact management system and add a gallery on the profiles
 * New universal-share bookmarklet
 * CSS animations and mobile integration (FirefoxOS and Android)
 * Internet Explorer 11 support
 * PHP7 Support

v0.8.1 – Polar Aurora
---------------------------
 * Add charts in the Statistics
 * Add a Caps support table
 * Fix some Jingle issues
 * New Mud actions to create/update the database and change the administration configuration
 * New InitAccount widget to create persistent PEP node on the first login
 * Clean the Feed widget
 * Fix various CSS bugs + fix mobile UI
 * Add title attribute to some truncated texts
 * Add a new fancy login system
 * Show the status in the Roster
 * Optimize the Presence handling
 * Improve the MUC presence handling
 * Improve the posts CSS
 * Add a fancy XEP visualisator

v0.8.0 – Polar Aurora
---------------------------

 * Refactor the whole Movim sourcecode + clean old code
 * Quite all the Movim widgets are now using a full MVC system
 * Rewrite the core session manager (Sessionx)
 * Add a new localisation system + new translations
 * Move the Movim librairies and dependencies to Composer and convert Modl and Moxl to PSR-0 to simplify the loading and packaging of the libraries
 * Monolog is now the new log library for Movim
 * Lots of warnings fixed
 * Add WebRTC threw Jingle audio-video conferencing
 * Make the UI fully responsible (from smartphone to FullHD screens)
 * The Roster widget has been totally rewriten
 * New picture library manager (with new thumbnail generation system)
 * Better MUC integration in the Chat widget
 * Rich text messages are now supported in the Chat widget
 * Add Vcard4 (http://xmpp.org/extensions/xep-0292.html) support in the profile
 * Implement the new official Movim API (https://api.movim.eu/)
 * Huge sourcecode optimisation
 * Rewrite the Administration panel and split it in many little widgets
 * Move the full configuration system to the database (except the database credentials)
 * List all the Movim network pods on a new page
 * Move the all UI to OpenSans
 * Add Title support during post publication
 * New statistics page for the administrators
 * Rewrite the infos page and move it to a widget, move the data structure from XML to JSON
 * Use SASL2 library (https://github.com/edhelas/sasl2) for the XMPP authentication and add SCRAM-SHA1 mechanism support
 * Split the Profile form in 3 littles forms (general, avatar and localisation)
 * Rewrite the Explore page
 * Move from XML to JSON for the browser-server requests
 * Update the locales

v0.7.2 – Sandstorm
---------------------------

 * Rewrite Modl to Modl2 with dynamic database update, PDO support (MySQL and PostgreSQL)
 * Add support of XEP-0084: User Avatar
 * Bug fixes in chatroom
 * Complete rewrite f the bookmark/subscription system
 * Huge code optimisation (x10 of some parts)
 * CSS fixes
 * Fix lot of issues on the groups (add youtube video support) + microblog
 * Add a new log system
 * Various minor bug fixed

v0.7.1 – Sandstorm
---------------------------

 * Huge speed optimisation
 * Fux UI fix
 * Implement picute insertion in posts
 * Chat fix
 * Smiley updated

v0.7.0 – Sandstorm
---------------------------

 * Media hosting and implementation (picture) @edhelas
 * Group implementation @edhelas @nodpounod
 * Datajar to Modl (https://github.com/edhelas/modl) portage @edhelas
 * Video + picture integration (gallery preview) @edhelas
 * Admin panel with hosting space administration @edhelas
 * URL rewriting @edhelas
 * Multi User Chat @edhelas

v0.6.1 – Cumulus
---------------------------

 * Fix SSL certificate problem

v0.6.0 – Cumulus
---------------------------

 * Create a new installer @kilian @edhelas
 * Create admin user interface to change conf.xml @edhelas
 * Improved user experience @edhelas

### Core @edhelas ###

 * 100% Moxl integration
 * Add Moxl support to build.sh

### Widgets @edhelas ###

#### Chat ####

 * Support “user is typing”

#### Roster ####

 * bidirectional friendrequests. Users can always see each other
 * little search box to filter the list (nodpounod)

#### Post ####

 * http://xmpp.org/extensions/xep-0071.html some basic WYSIWYG
 * Provide public/private posts

### Datajar ###

 * Support updating of db-schemas.

### Translations ###

 * Pull new translations automaticly into trunk
 * Add new translations

### Moxl ###
 * Support of the XEP-0115 Entity Capabilities, which enables the client to communicate its features and the extent of its XMPP support to the server
 * Implementation of DIGEST-MD5 and CRAM-MD5 as more secure log-in mechanisms

v0.5.0 – Snowball
---------------------------

 * Parse all the Movim messages to make them more “user-friendly” (smileys, links, bb-code like) @Etenil
 * DONE Make a public XML page reporting on the pod status (how many user hosted, version, current status…), to be pinged from pod.movim.eu @edhelas
 * Move DataJar based Classes into a single folder @edhelas
 * Cleaner CSS @edhelas
 * Update dates (like “2 min ago”) automatically @edhelas
 * Clean and move UserConf in a single class @edhelas
 * New UI @edhelas

### Core ###

 * Integrate Datajar @etenil
 * Test Movim on all Datajar back-ends @etenil
 * Write a makefile to manage packaging/pulling dependencies @etenil
 * Provide a more consistent API for the XMPP library (to ease the replacement of JAXL later) @etenil
 * Store the Caps (XEP-115) in the database to cache them @edhelas

### Widgets @edhelas ###

 * Move Profile to a single page
 * Merge “News” and “Feed” in one single widget and create filters (by source, date…)
 * Create a system to cache the Widgets

#### Roster ####

 * Add groups support
 * Fixed Bug : chat link when a contact become online

#### Profile ####

 * New system to switch the presences
 * Change the status

#### Feed/Wall ####

 * Store comments in the database
 * Add comments
 * Show/hide old comments if there is a lot of them (like 2 or more)

#### vCard ####

 * Add Avatar support
 * Date picker for the birth date (kilian)
 * Display client informations

#### Chat ####

 * More consistent UI
 * Store all the Messages in the database to handle them more cleanly

v0.4.0
---------------------------

 * Multisession support
 * Dynamically modify page title
 * image.php to built pictures from the database + ask the browser to cache them
 * Inscription on the Server (XMPP+Movim)
 * HTML5 + HTML Title page notification on a new message
 * Support of HTTP Proxy (installation and configuration)
 * Support of HTTPS Servers
 * Implementation on ORDERBY in the Storage database library
 * Fix language selector
 * Fix Roster display and organisation
 * Fix Chat display
 * Rename some widgets
 * Fix Vcard widget

v0.3.0
---------------------------

 * Widgets debugging
 * Enlarge widgets
 * Notifications
 * Blinking tab title
 * Coloured nicknames
 * Cached conversation
 * Tabbed conversations
 * Blocks-based layout
 * More bug fixes
 * URL Rewriting
 * Logger

v0.2.0
---------------------------

 * Inter-widgets communication
 * Proper disconnection handling
 * Added Installer
 * Changed to static loading
 * Speed optimisations
 * Improved Javascript libraries
 * Added unit-testing structure
 * Restructured the program
 * Reimplemented PHP's session
 * Added Cache
 * Use of SQLite3 as Cache/Session back-end (only for 0.2)
 * Improved theme

v0.1.0
---------------------------

 * Base core
 * Events system
 * Configuration
 * XMPP connection
 * Widget system

