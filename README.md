# Installation

Copy all files inside your web directory accessible from your **App's URL**.
 
cf. https://support.boondmanager.com/hc/fr/articles/209465426-Create-your-App

# Set application's config
- Fill the following variables inside `init.php`:

|  variable | description | example |
| --- | --- | --- |
| **{{APP_KEY}}** |  Your **App's Key** | `0000aaaa0000aaaa0000` |
| **{{APP_CODE}}** | Your **Installation's Code** | `` |

Please note that the Installation code is not mandatory and is NOT the App Code from the App's configuration page. 
You can set an arbitrary code in the init.php file, which will act like a password to allow a user to install the app. This code will then be entered by the user when installing the app in Boondmanager.

cf. https://support.boondmanager.com/hc/fr/articles/209465426-Create-your-App

cf. https://support.boondmanager.com/hc/fr/articles/209465446-Install-your-App


