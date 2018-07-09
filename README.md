# Setup Share 1.2
A simple App for sharing setups within Assetto Corsa sessions.

## App

The app works on saved setups/pit strategyies used by your car and track in the current session (online or single player). You can only download setups from other drivers that already uploaded their setups for the same combo.

### App install

First unzip the release content direct on your assetto corsa main folder (C:/Program Files (x86)/steam/steamapps/common/assettocorsa) and load the game.
Select the option menu and the general sub menu in-game to activate the SetupShare app. In the UI Module section will be listed this app to be checked.
![Enabling the app in-game.](https://raw.githubusercontent.com/albertowd/SetupShare/master/img/SS-Menu.gif)
Last step is to enter a session (online, practice, race..) and select it on the right app bar to see it on screen.

### Downloading and uploading

This app doesn't apply a downloaded setup automatically neither upload an unsaved one.
The first line of the app contains the list of the users computer setups. The selected setup can be uploaded to the system, updating it if it's already in "the cloud".
The rest of the view contains the list of uploaded setups groupped by users. Selecting one and downloading it will write or overwrite the setup on the users computer.
![Using the app.](https://raw.githubusercontent.com/albertowd/SetupShare/master/img/SS-Session.gif)

## Web

The [page](http://albertowd.com.br/setupshare/) shows all the uploaded setups, use the search filter to find and download one (.ini and .sp, if available). Uploads are only allowed through the app.

## Changelog

v 1.2:
    * App: adapted to the handle the new server.
    * Server: Chenged setups files to a database.